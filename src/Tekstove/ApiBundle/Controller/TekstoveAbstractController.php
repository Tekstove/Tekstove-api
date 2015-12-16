<?php

namespace Tekstove\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;

/**
 * Description of TekstoveAbstractController
 *
 * @author po_taka
 */
class TekstoveAbstractController extends FOSRestController
{
    private $context;
    /**
     * @return SerializationContext
     */
    public function getContext()
    {
        if ($this->context === null) {
            $this->context = SerializationContext::create();
        }
        
        return $this->context;
    }
    
    public function handleData($data)
    {
        $data = $this->propelQueryToPagination($data);
        $data = $this->paginationToArray($data);
        
         $view = $this->view($data, 200);
         $view->setSerializationContext($this->getContext());
         return $view;
    }
    
    public function propelQueryToPagination($query)
    {
        /**
         * This methods should be listner or service!
         * @todo
         * @fixmes
         */
        $request = $this->getRequest();
        
        if (!$query instanceof \Propel\Runtime\ActiveQuery\ModelCriteria) {
            return $query;
        }
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        
        return $pagination;
    }
    
    public function paginationToArray($pagination)
    {
        if (!$pagination instanceof \Knp\Component\Pager\Pagination\AbstractPagination) {
            return $pagination;
        }
        
        $data = [
            'items' => $pagination->getItems(),
            'pagination' => [
                'currentPage' => $pagination->getCurrentPageNumber(),
                'itemNumberPerPage' => $pagination->getItemNumberPerPage(),
                'totalItemCount' => $pagination->getTotalItemCount(),
            ],
        ];
        
        return $data;
    }
    
}