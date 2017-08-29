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
            $this->context->setSerializeNull(true);
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
        $this->applyPaginationOptions($request);
        $data = $this->applyFilters($request, $data);
        $data = $this->applyOrders($request, $data);
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
    
    protected function applyPaginationOptions(Request $request)
    {
        $limit = $request->get('limit', 10);
        $this->setItemsPerPage($limit);
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
        
        foreach ($groups as $group) {
            if ($group === 'Credentials') {
                throw new \Exception('Credentials group can\'t be set!');
            }
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
            $operator = strtoupper($filter['operator']);
            $field = $filter['field'];
            //@TODO maybe this should be service
            $filterMethod = 'filterBy' . ucfirst($field);
            switch ($operator) {
                case Criteria::EQUAL:
                case Criteria::GREATER_THAN:
                case Criteria::IN:
                case Criteria::LIKE:
                    $data->filterBy($field, $value, $operator);
                    break;
                case 'NOT_NULL':
                    $data->{$filterMethod}(null, Criteria::ISNOTNULL);
                    break;
                case 'RANGE':
                    if (!array_key_exists('min', $value) && !array_key_exists('min', $value)) {
                        throw new \Exception("Please set `min` or `max` for {$filterMethod}");
                    }
                    $data->{$filterMethod}($value);
                    break;
                case 'OR':
                    $condition = 1;
                    throw new \Exception('Not implemented');
                    break;
                default:
                    throw new \Exception("Unknown operator {$operator}");
            }
        }
        
        // @FIXME this is test data, remove it!
        $this->generateCompositeCriterion(
            [
                'operator' => 'or',
                'value' => [
                    [
                        'field' => 'id',
                        'value' => '400 or 1=1',
                        'operator' => '=',
                    ],
                    [
                        'field' => 'id',
                        'value' => 401,
                        'operator' => '=',
                    ],
                    [
                        'field' => 'id',
                        'value' => 402,
                        'operator' => '=',
                    ],
                ],
            ], 
            $data
        );
        
        return $data;
    }
    
    protected function generateCompositeCriterion(array $data, \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria)
    {
        // @TODO validate operator!
        $operator = $data['operator'];
        $conditionsCollection = $data['value'];
        
        $tableMap = $modelCriteria->getTableMap();
        
        $criterionsCollection = [];
        
        foreach ($conditionsCollection as $conditionData) {
            // lets assume that there are no nesting
            if (!$tableMap->hasColumnByPhpName($conditionData['field']) && !$tableMap->hasColumnByPhpName(ucfirst($conditionData['field']))) {
                dump($tableMap); die;
                throw new \Exception("Unknown field {$conditionData['field']}");
            }

            switch ($conditionData['operator']) {
                case Criteria::EQUAL:
                case Criteria::GREATER_THAN:
                case Criteria::IN:
                case Criteria::LIKE:
                    break;
                default:
                    throw new \Exception("Unknown operator {$conditionData['operator']}");
            }
            
            $criterion = $modelCriteria->getNewCriterion(
                $conditionData['field'], 
                $conditionData['value'], 
                $conditionData['operator']
            );
            
            $criterionName = uniqid();
            $criterionsCollection[$criterionName] = $criterion;
            $modelCriteria->addCond($criterionName, $criterion);
        }
        
        
        $modelCriteria->combine(
            array_keys($criterionsCollection),
            $operator
        );
    }

    protected function applyOrders(Request $request, $data)
    {
        if (!$data instanceof \Propel\Runtime\ActiveQuery\ModelCriteria) {
            return $data;
        }
        
        $sortData = $request->get('order', []);
        
        if (!empty($sortData)) {
            // delete default order
            $data->clearOrderByColumns();
        }
        
        foreach ($sortData as $order) {
            $field = $order['field'];
            $direction = $order['direction'];
            // $field is not CameCase cuz magic method expect
            // same name as property :(
            $orderMethod = 'orderBy' . ($field);
            $data->{$orderMethod}($direction);
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

    protected function propelSetter($object, $values, $setter)
    {
        // @TODO @FIXME
        
        if ($setter == 'setTopic') {
            $topicQ = new \Tekstove\ApiBundle\Model\Forum\TopicQuery();
            $topic = $topicQ->findOneById($values);
            $object->setTopic($topic);
            return true;
        }
        
        
        if (!is_array($values)) {
            // @TODO use property accessor!
            $getter = preg_replace('/^set/', 'get', $setter);
            $originalData = $object->$getter();
            if ($originalData instanceof \Propel\Runtime\Collection\Collection) {
                $object->$setter(new \Propel\Runtime\Collection\ArrayCollection());
            } else {
                $object->$setter($values);
            }
            return true;
        }
        
        $relectionClass = new \ReflectionClass($object);
        
        $mappClass = $relectionClass->getNamespaceName() . '\\Map\\' . $relectionClass->getShortName() . 'TableMap';
        $tableMap = $mappClass::getTableMap();
        /* @var $tableMap \Propel\Runtime\Map\TableMap */
        
        $setterWithoutSet = preg_replace('/^set([A-Z])/', '$1', $setter);
        $setterSingular = preg_replace('/s$/', '', $setterWithoutSet);
        
        $relation = $tableMap->getRelation($setterSingular);
        /* @var $relation \Propel\Runtime\Map\RelationMap */
        $foreignClass = $relation->getLocalTable()->getClassName();
        $primaryKeys = $relation->getLocalTable()->getPrimaryKeys();
        if (count($primaryKeys) > 1) {
            throw new \Exception("Not implemented for composite PK");
        }
        
        $primaryKey = current($primaryKeys);
        /* @var $primaryKey \Propel\Runtime\Map\ColumnMap */
        $idName = $primaryKey->getPhpName();
        
        $foreignClassQuery = $foreignClass . 'Query';
        $foreignQuery = new $foreignClassQuery();
        /* @var $foreignQuery \Propel\Runtime\ActiveQuery\ModelCriteria */
        
        $itemsToMap = new \Propel\Runtime\Collection\ArrayCollection();
        foreach ($values as $value) {
            $foreignQuery = new $foreignClassQuery();
            /* @var $foreignQuery \Propel\Runtime\ActiveQuery\ModelCriteria */
            $mapObject = $foreignQuery->findOneBy($idName, $value);
            if (empty($mapObject)) {
                throw new \Exception("Can't find #{$value}");
            }
            $itemsToMap->append($mapObject);
        }
        
        $object->$setter($itemsToMap);
    }
    
    protected function userMustBeLogged()
    {
        $securityChecker = $this->get('security.authorization_checker');
        if (!$securityChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
    }
}
