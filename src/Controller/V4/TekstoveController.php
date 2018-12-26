<?php

namespace App\Controller\V4;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Context\Context;

class TekstoveController extends FOSRestController
{
    private $serializer;
    private $context;

    public function __construct(\JMS\Serializer\SerializerInterface $serializer, RequestStack $r)
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

        // @FIXME remove snsitive data!
        array_walk(
            $groups,
            function (string &$item) {
                $item = strtolower($item);
            }
        );
        $this->getContext()->setGroups($groups);
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function handleData($data): Response
    {
        if (is_string($data)) {
            $repo = $this->getDoctrine()->getRepository($data);
            $qb = $repo->createQueryBuilder('d');
            /* @var $qb QueryBuilder */

            // @FIXME hardcoded filters
            $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->gte('d.id', 50)
                )
            );

            $qb->setMaxResults(10); // @FIXME max result dynamic
            $entities = $qb->getQuery()->getResult();
            $data = $entities;
        }

        if (!is_array($data)) {
            $data = [
                'item' => $data,
            ];
        }

        $view = $this->view($data, 200);
        $view->setContext($this->getContext());
        return $this->handleView($view);
    }

    /**
     * @return Context
     */
    protected function getContext()
    {
        if ($this->context === null) {
            $this->context = new Context();
            $this->context->setSerializeNull(true);
        }

        return $this->context;
    }
}
