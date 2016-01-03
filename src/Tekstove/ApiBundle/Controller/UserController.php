<?php

namespace Tekstove\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UserController extends TekstoveAbstractController
{
    /**
     * @Template();
     */
    public function indexAction(Request $request)
    {
        $userQuery = new \Tekstove\ApiBundle\Model\UserQuery();
        
        $this->getContext()
                ->setGroups(['List']);
        
        return $this->handleData($request, $userQuery);
    }
}
