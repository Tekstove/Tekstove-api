<?php

namespace App\Controller\V4;

use App\Entity\User\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Context\Context;
use Tekstove\ApiBundle\Model\User as UserV2;

class TekstoveController extends AbstractFOSRestController
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $currentRequest;

    /**
     * Serialization context
     * @var Context
     */
    private $context;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    private $request;

    public function __construct(RequestStack $r, PaginatorInterface $pager)
    {
        $this->currentRequest = $r->getCurrentRequest();
        $this->setGroups($r->getCurrentRequest());
        $this->paginator = $pager;
    }

    protected function setGroups(Request $request)
    {
        $groups = $this->currentRequest->query->get('groups');

        if (empty($groups)) {
            $method = $request->getMethod();
            // groups are not required for post/path methods
            if (in_array($method, [Request::METHOD_POST, Request::METHOD_PATCH])) {
                $groups = ['not-existing-group'];
            } else {
                throw new \RuntimeException('Groups can\'t by empty');
            }
        }

        // here we should remove groups allowing personal data serialization
        array_walk(
            $groups,
            function (string &$item) {
                $item = strtolower($item);
            }
        );
        $this->getContext()->setGroups($groups);
    }

    /**
     * Return current logged user.
     * Transform Model\User to Entity\User
     */
    public function getUser()
    {
        $user = parent::getUser();
        if ($user instanceof UserV2) {
            $useRepo = $this->getDoctrine()->getRepository(User::class);
            $userV4 = $useRepo->findOneBy(['id' => $user->getId()]);
            return $userV4;
        }

        return $user;
    }

    /**
     * Create Response from array
     */
    public function handleArray(array $data): Response
    {
        $view = $this->view($data, 200);
        $view->setContext($this->getContext());
        return $this->handleView($view);
    }

    /**
     * Create Response from QueryBuilder
     */
    public function handleQueryBuilder(QueryBuilder $qb): Response
    {
        $this->applyFilters(
            $this->currentRequest->get('filters', []),
            $qb,
            'd'
        );

        $orders = $this->currentRequest->get('order', []);
        foreach ($orders as $order) {
            $field = $order['field'];
            $direction = $order['direction'];

            $qb->addOrderBy('d.' . $field, $direction);
        }

        // Pagination
        $pagination = $this->paginator->paginate(
            $qb,
            $this->currentRequest->query->getInt('page', 1),
            $this->getItemsPerPage()
        );

        $data = [
            'items' => $pagination->getItems(),
            'pagination' => [
                'currentPage' => $pagination->getCurrentPageNumber(),
                'itemNumberPerPage' => $pagination->getItemNumberPerPage(),
                'totalItemCount' => $pagination->getTotalItemCount(),
            ],
        ];

        return $this->handleArray($data);
    }

    /**
     * Create Response from EntityRepository
     */
    public function handleRepository(EntityRepository $repo): Response
    {
        $qb = $repo->createQueryBuilder('d');
        /* @var $qb QueryBuilder */

        return $this->handleQueryBuilder($qb);
    }

    /**
     * Apply array filters to QueryBuilder
     */
    private function applyFilters(array $filterCollection, QueryBuilder $queryBuilder, string $baseEntityName)
    {
        $simpleOperators = [
            '=' => 'eq',
            'like' => 'like',
            'in' => 'in',
            '>' => 'gt',
        ];

        foreach ($filterCollection as $filter) {
            $operator = strtolower($filter['operator']);
            $field = $filter['field'];

            if (array_key_exists($operator, $simpleOperators)) {
                $methodName = $simpleOperators[$operator];
                $paramName = uniqid('p');

                if (strpos($field, '.') !== false) {
                    /*
                     * rootEntity.Join.Column
                     */
                    $fieldExploded = explode('.', $field);
                    $queryBuilder->innerJoin(
                        $fieldExploded[0] . '.' . $fieldExploded[1],
                        $fieldExploded[1],
                        Join::WITH,
                        $queryBuilder->expr()->{$methodName}(
                            $fieldExploded[1] . '.' . $fieldExploded[2],
                            ':' . $paramName
                        )
                    );
                } else {
                    $queryBuilder->andWhere(
                        $queryBuilder->expr()->{$methodName}(
                            $baseEntityName . '.' . $field,
                            ':' . $paramName
                        )
                    );
                }

                $queryBuilder->setParameter($paramName, $filter['value']);

                continue;
            }
            switch ($operator) {
                case 'not_null':
                    $queryBuilder->andWhere(
                        $queryBuilder->expr()->isNotNull(
                            $baseEntityName . '.' . $field
                        )
                    );

                    break;
                default:
                    throw new \Exception("Unknown operator `{$operator}`");
            }
        }
    }

    private function getItemsPerPage($default = 10): int
    {
        $itemsPerPageRequest = (int)$this->currentRequest->get('limit', $default);
        if ($itemsPerPageRequest > 0) {
            return $itemsPerPageRequest;
        }

        return $default;
    }

    /**
     * Create response from single entity
     */
    public function handleEntity($entity): Response
    {
        $data = [
            'item' => $entity,
        ];

        return $this->handleArray($data);
    }

    protected function getContext(): Context
    {
        if ($this->context === null) {
            $this->context = new Context();
            $this->context->setSerializeNull(true);
        }

        return $this->context;
    }
}
