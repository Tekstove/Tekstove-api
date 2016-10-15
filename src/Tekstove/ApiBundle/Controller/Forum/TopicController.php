<?php

namespace Tekstove\ApiBundle\Controller\Forum;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Forum\TopicQuery;
use Tekstove\ApiBundle\Model\Acl\Permission;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Description of TopicController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TopicController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $topicQuery = new TopicQuery();
        
        $user = $this->getUser();
        /* @var $user \Tekstove\ApiBundle\Model\User */
        if (!$user || !$user->getPermission(Permission::FORUM_VIEW_SECRET)) {
            $categoryQuery = new \Tekstove\ApiBundle\Model\Forum\CategoryQuery();
            $categoryQuery->filterByHidden(1);
            $hiddenCategories = $categoryQuery->find();
            $hiddenCategoryIds = [];
            // @TODO cache ?
            foreach ($hiddenCategories as $category) {
                $hiddenCategoryIds[] = $category->getId();
            }
            $topicQuery->filterByForumCategoryId($hiddenCategoryIds, Criteria::NOT_IN);
        }
        $topicQuery->orderById(Criteria::DESC);
        return $this->handleData($request, $topicQuery);
    }
    
    public function getAction(Request $request, $id)
    {
        $this->applyGroups($request);
        $topicQuery = new TopicQuery();
        $user = $this->getUser();
        /* @var $user \Tekstove\ApiBundle\Model\User */
        if (!$user || !$user->getPermission(Permission::FORUM_VIEW_SECRET)) {
            $categoryQuery = new \Tekstove\ApiBundle\Model\Forum\CategoryQuery();
            $categoryQuery->filterByHidden(1);
            $hiddenCategories = $categoryQuery->find();
            $hiddenCategoryIds = [];
            // @TODO cache ?
            foreach ($hiddenCategories as $category) {
                $hiddenCategoryIds[] = $category->getId();
            }
            $topicQuery->filterByForumCategoryId($hiddenCategoryIds, Criteria::NOT_IN);
        }
        
        $topic = $topicQuery->findOneById($id);
        
        return $this->handleData($request, $topic);
    }
}
