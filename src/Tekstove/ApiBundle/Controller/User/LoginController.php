<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController;
use Symfony\Component\HttpFoundation\Request;

use Propel\Runtime\ActiveQuery\Criteria;

use Tekstove\ApiBundle\Model\UserQuery;

class LoginController extends TekstoveAbstractController
{
    public function postAction(Request $request, $username)
    {
        $this->getContext()->setGroups(['Details', 'Credentials']);
        $userQuery = new UserQuery();
        $userQuery->filterByUsername($username, Criteria::EQUAL);
        
        
        $password = $request->get('password');
        $passwordHashed = md5($password);
        $userQuery->filterByPassword($passwordHashed, Criteria::EQUAL);
        
        return $this->handleData($request, $userQuery);
    }
}
