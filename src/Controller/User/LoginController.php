<?php

namespace App\Controller\User;

use App\Controller\TekstoveAbstractController;
use Symfony\Component\HttpFoundation\Request;

use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\Model\UserQuery;
use Tekstove\ApiBundle\Model\User;

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
        // only active users should be able to login,
        // unfortunately only status "deleted" is used atm
        $userQuery->filterBystatus(User::STATUS_DELETED, Criteria::ALT_NOT_EQUAL);
        $user = $userQuery->findOne();

        return $this->handleData($request, $user);
    }

    public function getAction(Request $request)
    {
        $this->userMustBeLogged();
        $user = $this->getUser();

        $this->getContext()->setGroups(['Details', 'Credentials']);
        return $this->handleData($request, $user);
    }
}
