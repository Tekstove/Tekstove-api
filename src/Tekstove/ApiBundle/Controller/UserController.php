<?php

namespace Tekstove\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\UserQuery;
use Tekstove\ApiBundle\Model\User\Exception\UserHumanReadableException;

class UserController extends TekstoveAbstractController
{
    /**
     * @Template()
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

    public function patchAction(Request $request, $id)
    {
        $this->getContext()
                ->setGroups(['List']);

        $repo = $this->get('tekstove.user.repository');
        /* @var $repo UserQuery */
        $userQuery = new UserQuery();
        /* @var $user \Tekstove\ApiBundle\Model\User */
        $user = $userQuery->requireOneById($id);
        
        try {
            $content = $request->getContent();
            $pathData = json_decode($content, true);
            $pathPopulator = $this->get('tekstove.api.populator.patch');
            /* @var $pathPopulator \Tekstove\ApiBundle\Populator\PathPopulator */
            $pathPopulator->populateObject($pathData, $user);
            $repo->save($user);

            return $this->handleData($request, $user);
        } catch (UserHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);

            return $view;
        }
    }

    public function deleteAction($id)
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            throw new \Exception("User not logged in!");
        }

        $userQuery = new UserQuery();
        $user = $userQuery->findOneById($id);
        if (!$user) {
            throw new \Exception('User not found!');
        }

        /* @var $user \Tekstove\ApiBundle\Model\User */
        $deletedName = 'unknown-' . $user->getId();

        $user->setstatus(\Tekstove\ApiBundle\Model\User::STATUS_DELETED);

        $user->setUsername($deletedName);
        $user->setMail($deletedName . '@localhost.local');
        $user->setAvatar(null);
        $user->setAbout(null);
        $user->settermsAccepted(new \DateTime()); // needed for validation

        // credentials
        $user->setPassword($deletedName);
        $user->setapiKey(md5(uniqid($deletedName)));

        $user->save();

        return new \Symfony\Component\HttpFoundation\Response();
    }
}
