<?php

namespace Tekstove\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;

class UserController extends FOSRestController
{
    /**
     * @Template();
     */
    public function indexAction(Request $request)
    {
        $userQuery = new \Tekstove\ApiBundle\Model\UserQuery();
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $userQuery,
            $request->query->getInt('page', 1),
            10
        );
        
        /* @var $pagination \Knp\Component\Pager\Pagination\AbstractPagination */
        
        $data = $pagination->getItems();
        $view = $this->view($data, 200);
        
        $context = SerializationContext::create();
        $context->setGroups(['List']);
        
        $view->setSerializationContext($context);
        return $view;
    }
}
