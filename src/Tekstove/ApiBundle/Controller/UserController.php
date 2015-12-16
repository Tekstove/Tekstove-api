<?php

namespace Tekstove\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends TekstoveAbstractController
{
    /**
     * @Template();
     */
    public function indexAction()
    {
        $userQuery = new \Tekstove\ApiBundle\Model\UserQuery();
        
        $this->getContext()
                ->setGroups(['List']);
        
        return $this->handleData($userQuery);
    }
}
