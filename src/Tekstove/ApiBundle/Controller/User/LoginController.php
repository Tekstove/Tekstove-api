<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController;
use Symfony\Component\HttpFoundation\Request;

use Propel\Runtime\ActiveQuery\Criteria;

use Tekstove\ApiBundle\Model\UserQuery;

class LoginController extends TekstoveAbstractController
{
    public function postAction(Request $request)
    {
        $this->getContext()->setGroups(['Details', 'Credentials']);
        
        $content = $request->getContent();
        $loginData = json_decode($content, true);
        
        $username = $loginData['username'];
        $password = $loginData['password'];
        // @TODO use encoder!
        $passwordHashed = md5($password);
        
        $userQuery = new UserQuery();
        $userQuery->filterByUsername($username, Criteria::EQUAL);
        $userQuery->filterByPassword($passwordHashed, Criteria::EQUAL);
        
        return $this->handleData($request, $userQuery);
    }
}
