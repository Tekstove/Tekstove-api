<?php

namespace App\Controller\V4;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Context\Context;
use JMS\Serializer\SerializerInterface;

class TekstoveController extends FOSRestController
{
    private $serializer;

    /**
     * Serialization context
     * @var Context
     */
    private $context;

    public function __construct(SerializerInterface $serializer, RequestStack $r)
    {
        $this->serializer = $serializer;
        $groups = $r->getCurrentRequest()->query->get('groups');
        $this->setGroups($groups);
    }

    protected function setGroups(array $groups)
    {
        if (empty($groups)) {
            throw new \RuntimeException('Groups can\'t by empty');
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
     * @param array $data
     * @return Response
     */
    public function handleArray(array $data): Response
    {
        $view = $this->view($data, 200);
        $view->setContext($this->getContext());
        return $this->handleView($view);
    }

    public function handleRepository(EntityRepository $repo): Response
    {
        $qb = $repo->createQueryBuilder('d');
        /* @var $qb QueryBuilder */

        // filters goes here

        // pagination should be dynamic
        $qb->setMaxResults(10);

        $entities = $qb->getQuery()->getResult();
        $data = ['items' => $entities];

        return $this->handleArray($data);
    }

    public function handleEntity($entity): Response
    {
        $data = [
            'item' => $entity,
        ];

        return $this->handleArray($data);
    }

    /**
     * @return Context
     */
    protected function getContext(): Context
    {
        if ($this->context === null) {
            $this->context = new Context();
            $this->context->setSerializeNull(true);
        }

        return $this->context;
    }
}
