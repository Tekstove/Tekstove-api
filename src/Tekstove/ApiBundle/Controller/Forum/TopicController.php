<?php

namespace Tekstove\ApiBundle\Controller\Forum;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Forum\TopicQuery;
use Tekstove\ApiBundle\Model\Acl\Permission;
use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\Model\Forum\Topic;
use Tekstove\ApiBundle\Model\Forum\Post;
use Tekstove\ApiBundle\Model\Forum\Topic\TopicHumanReadableException;

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
    
    public function postAction(Request $request)
    {
        $this->userMustBeLogged();
        
        $data = json_decode($request->getContent(), true);
        
        $categoryid = $data['category'];
        $name = $data['name'];
        $postText = $data['postText'];
        
        $categoryQuery = new \Tekstove\ApiBundle\Model\Forum\CategoryQuery();
        $category = $categoryQuery->requireOneById($categoryid);
        
        $topic = new Topic();
        $topic->setCategory($category);
        $topic->setUser($this->getUser());
        $topic->setName($name);
        $topicRepo = $this->get('tekstove.forum.topic.repository');
        
        try {
            $con = \Propel\Runtime\Propel::getConnection();
            $con->beginTransaction();

            $topicRepo->save($topic);

            $post = new Post();
            $post->setText($postText);
            $post->setUser($this->getUser());
            $post->setTopic($topic);
            $postRepo = $this->get('tekstove.forum.post.repository');
            $postRepo->save($post);

            $con->commit();
        } catch (TopicHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        } catch (Post\PostHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
        
        $this->getContext()->setGroups(['List']);
        return $this->handleData($request, $topic);
    }
}
