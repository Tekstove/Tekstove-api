<?php

namespace App\Controller\V4;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Context\Context;

class TekstoveController extends FOSRestController
{
    private $serializer;
    private $groups;
    private $context;

    public function __construct(\JMS\Serializer\SerializerInterface $serializer, RequestStack $r)
    {
        $this->serializer = $serializer;
        $groups = $r->getCurrentRequest()->query->get('groups');
        $this->setGroups($groups);
    }

    protected function setGroups(array $groups)
    {
        // @FIXME remove snsitive data!
        array_walk($groups, function (&$item) { $item = strtolower($item); });
        $this->getContext()->setGroups($groups);
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function handleData($data): Response
    {
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
