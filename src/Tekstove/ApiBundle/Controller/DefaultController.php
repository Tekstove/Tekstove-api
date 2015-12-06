<?php

namespace Tekstove\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{

    /**
     * @Template()
     */
    public function indexAction($name)
    {
        $return = 
                [
                    'name' => 'Angel'
                ]
        ;
        return $return;
    }
}
