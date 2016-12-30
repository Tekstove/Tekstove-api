<?php

namespace Tekstove\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Potaka\Helper\Casing\CaseHelper;

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
            $currentUser = $this->getUser();
            $allowedFields = $currentUser->getAllowedUserFields($user);

            $caseHelper = new CaseHelper();
            $content = $request->getContent();
            $pathData = json_decode($content, true);
            foreach ($allowedFields as $field) {
                foreach ($pathData as $path) {
                    switch ($path['op']) {
                        case 'replace':
                            if ($path['path'] === '/' . $field) {
                                $bumpyCase = $caseHelper->bumpyCase($field);
                                $setter = 'set' . $bumpyCase;
                                $value = $path['value'];
                                $user->{$setter}($value);
                            }
                            break;
                        default:
                            throw new \Exception('not implemented `op`');
                    }
                }
            }
            $repo->save($user);
            return $this->handleData($request, $user);
        } catch (UserHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
}
