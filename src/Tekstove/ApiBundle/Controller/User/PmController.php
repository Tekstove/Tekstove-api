<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\User\PmQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;

/**
 * PmController
 *
 * @author potaka
 */
class PmController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->userMustBeLogged();
        $user = $this->getUser();
        
        $this->applyGroups($request);
        $pmQuery = new PmQuery();
        
        if ($request->get('direction') === 'from') {
            $pmQuery->filterByUserFrom($user);
        } else {
            $pmQuery->filterByUserTo($user);
        }
        
        $pmQuery->orderById(Criteria::DESC);
        
        return $this->handleData($request, $pmQuery);
        
    }
}
