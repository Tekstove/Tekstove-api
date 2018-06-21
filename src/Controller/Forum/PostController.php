<?php

namespace App\Controller\Forum;

use App\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Forum\PostQuery;
use Tekstove\ApiBundle\Model\Acl\Permission;
use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\Model\Forum\Post;
use Tekstove\ApiBundle\Model\Forum\Post\PostHumanReadableException;

/**
 * PostController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class PostController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $posts = new PostQuery();

        $user = $this->getUser();
        /* @var $user \Tekstove\ApiBundle\Model\User */
        if (!$user || !$user->getPermission(Permission::FORUM_VIEW_SECRET)) {
            $posts->useTopicQuery()
                  ->joinCategory()
                        ->addAnd('hidden', '0')
                  ->endUse()
            ;
        }

        $posts->orderBy('id', Criteria::ASC);

        return $this->handleData($request, $posts);
    }

    public function postAction(Request $request)
    {
        $this->userMustBeLogged();

        $this->getContext()->setGroups(['List']);

        $postDataJson = $request->getContent();
        $postData = json_decode($postDataJson, true);

        $post = new Post();
        $post->setUser($this->getUser());
        $post->setText($postData['text']);
        $this->propelSetter($post, $postData['topic'], 'setTopic');

        try {
            $postRepo = $this->get('tekstove.forum.post.repository');
            $postRepo->save($post);
            return $this->handleData($request, $post);
        } catch (PostHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
}
