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
        $posts->orderBy('id', Criteria::ASC);
        // @TODO filter hidden categories
        
        return $this->handleData($request, $posts);
    }
}
