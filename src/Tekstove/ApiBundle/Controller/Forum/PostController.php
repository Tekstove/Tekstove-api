<?php

namespace Tekstove\ApiBundle\Controller\Forum;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Forum\PostQuery;
use Tekstove\ApiBundle\Model\Acl\Permission;
use Propel\Runtime\ActiveQuery\Criteria;

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
}
