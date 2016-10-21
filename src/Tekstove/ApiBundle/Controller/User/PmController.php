<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\User\PmQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * PmController
 *
 * @author potaka
 */
class PmController extends Controller
{
    public function indexAction($request)
    {
        $this->userMustBeLogged();
        
        $pmQuery = new PmQuery();
        
        $pmQuery->orderById(Criteria::DESC);
        
        return $this->handleData($request, $pmQuery);
        
    }
}