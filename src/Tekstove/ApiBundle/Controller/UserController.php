<?php

namespace Tekstove\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\UserQuery;

class UserController extends TekstoveAbstractController
{
    /**
     * @Template();
     */
    public function indexAction(Request $request)
    {
        $userQuery = new UserQuery();
        
        $this->applyGroups($request);
        
        return $this->handleData($request, $userQuery);
    }
    
    /**
     * @Template()
     */
    public function getAction(Request $request, $id)
    {
        $this->applyGroups($request);
        $userQuery = new UserQuery();
        $user = $userQuery->requireOneById($id);
        return $this->handleData($request, $user);
    }
}
