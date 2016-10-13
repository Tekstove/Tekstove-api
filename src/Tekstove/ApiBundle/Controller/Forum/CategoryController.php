<?php

namespace Tekstove\ApiBundle\Controller\Forum;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Forum\CategoryQuery;
use Tekstove\ApiBundle\Model\Acl\Permission;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * CategoryController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class CategoryController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $categories = new CategoryQuery();
        $categories->setLimit(999);
        
        $user = $this->getUser();
        /* @var $user \Tekstove\ApiBundle\Model\User */
        if (!$user || !$user->getPermission(Permission::FORUM_VIEW_SECRET)) {
            $categories->addAnd('hidden', 0, Criteria::EQUAL);
        }
        return $this->handleData($request, $categories);
    }
    
    public function getAction(Request $request, $id)
    {
        $this->applyGroups($request);
        $categories = new CategoryQuery();
        
        $categories->filterById($id, Criteria::EQUAL);
        
        $user = $this->getUser();
        /* @var $user \Tekstove\ApiBundle\Model\User */
        if (!$user || !$user->getPermission(Permission::FORUM_VIEW_SECRET)) {
            $categories->addAnd('hidden', 0, Criteria::EQUAL);
        }
        return $this->handleData(
            $request,
            $categories->findOne()
        );
    }
}
