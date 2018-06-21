<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Request;

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
         $view->setContext($this->getContext());
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

        $filters = $request->get('filters', null);

        if ($filters) {
            $criteriaData = [
                'operator' => 'and',
                'value' => $filters,
            ];

            $criteriaGenerator = new \Tekstove\ApiBundle\Repo\CriteriaGenerator();
            $criterionName = $criteriaGenerator->generateCompositeCriterion($criteriaData, $data);
            $data->combine([$criterionName]);
        }

        return $data;
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
