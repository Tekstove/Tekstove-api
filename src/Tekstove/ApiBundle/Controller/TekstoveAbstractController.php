<?php

namespace Tekstove\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;

use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Description of TekstoveAbstractController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TekstoveAbstractController extends FOSRestController
{
    private $itemsPerPage = 10;
    
    private $context;
    /**
     * @return SerializationContext
     */
    protected function getContext()
    {
        if ($this->context === null) {
            $this->context = SerializationContext::create();
        }
        
        return $this->context;
    }
    
    /**
     * @param Request $request
     * @param type $data
     * @return \FOS\RestBundle\View\View
     */
    protected function handleData(Request $request, $data)
    {
        $data = $this->applyFilters($request, $data);
        $data = $this->propelQueryToPagination($request, $data);
        $data = $this->paginationToArray($data);
        
        if (!is_array($data)) {
            $data = [
                'item' => $data,
            ];
        }
        
         $view = $this->view($data, 200);
         $view->setSerializationContext($this->getContext());
         return $view;
    }
    
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    protected function getGroups(Request $request)
    {
        return $request->get('groups');
    }
    
    protected function applyGroups(Request $request)
    {
        $groups = $this->getGroups($request);
        if (empty($groups)) {
            throw new \Exception("Groups can't be empty");
        }
        $this->getContext()
                ->setGroups($groups);
    }
    
    protected function applyFilters(Request $request, $data)
    {
        if (!$data instanceof \Propel\Runtime\ActiveQuery\ModelCriteria) {
            return $data;
        }
        
        $filters = $request->get('filters', []);
        foreach ($filters as $filter) {
            $value = $filter['value'];
            $operator = $filter['operator'];
            $field = $filter['field'];
            //@TODO maybe this should be service
            $filterMethod = 'filterBy' . ucfirst($field);
            switch ($operator) {
                case '=':
                    $data->{$filterMethod}($value, $operator);
                    break;
                case 'NOT_NULL':
                    $data->{$filterMethod}(null, Criteria::ISNOTNULL);
                    break;
                case 'in':
                    $data->{$filterMethod}($value, Criteria::IN);
                    break;
                default:
                    throw new \Exception("Unknown operator {$operator}");
            }
        }
        
        return $data;
    }
    
    protected function propelQueryToPagination(Request $request, $query)
    {
        if (!$query instanceof \Propel\Runtime\ActiveQuery\ModelCriteria) {
            return $query;
        }
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $this->itemsPerPage
        );
        
        return $pagination;
    }
    
    protected function paginationToArray($pagination)
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
