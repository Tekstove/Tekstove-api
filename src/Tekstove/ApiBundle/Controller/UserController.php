<?php

namespace Tekstove\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;

class UserController extends FOSRestController
{
    /**
     * @Template();
     */
    public function indexAction()
    {
        $data = ['adsasdasd', 'wwww',];
        return $data;
    }
    
    /**
     * @Template();
     */
    public function getUsersAction()
    {
        $data = ['adsasdasd222', 'wwww',];
        return $data;
    }
}
