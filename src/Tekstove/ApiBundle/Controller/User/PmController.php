<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\User\PmQuery;
use Tekstove\ApiBundle\Model\User\Pm\Exception\PmHumanReadableException;
use Potaka\Helper\Casing\CaseHelper;

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
            $pmQuery->filterByUserRelatedByUserFrom($user);
        } else {
            $pmQuery->filterByUserRelatedByUserTo($user);
        }
        
        $pmQuery->orderByRead();
        $pmQuery->orderById(Criteria::DESC);
        
        return $this->handleData($request, $pmQuery);
    }
    
    public function getAction(Request $request, $id)
    {
        $this->userMustBeLogged();
        $user = $this->getUser();
        
        $this->applyGroups($request);
        $pmQuery = new PmQuery();
        $pmQuery->filterByUserSenderOrReceiver($user);
        
        $pm = $pmQuery->requireOneById($id);
        return $this->handleData($request, $pm);
    }
    
    public function postAction(Request $request)
    {
        $this->userMustBeLogged();
        
        $pmRepository = $this->get('tekstove.user.pm.repository');
        /* @var $pmRepository PmQuery */
        
        $user = $this->getUser();
        
        $data = json_decode($request->getContent(), true);
        
        $pm = new \Tekstove\ApiBundle\Model\User\Pm();
        $pm->setUserRelatedByUserFrom($user);
        
        $userQuery = new \Tekstove\ApiBundle\Model\UserQuery();
        $userTo = $userQuery->requireOneById($data['userTo']);
        $pm->setUserRelatedByUserTo($userTo);
        
        $pm->setTitle($data['title']);
                
        $pm->setText($data['text']);
        
        try {
            $pmRepository->save($pm);
        } catch (PmHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
        
        $this->getContext()->setGroups(['List']);
        return $this->handleData($request, $pm);
    }
    
    public function patchAction(Request $request, $id)
    {
        $this->userMustBeLogged();
        $user = $this->getUser();
        
        $this->getContext()
                ->setGroups(['List']);
        
        $repo = $this->get('tekstove.user.pm.repository');
        /* @var $repo PmQuery */
        $repo->filterByUserRelatedByUserTo($user);
        $post = $repo->findOneById($id);
        
        try {
            $allowedFields = $user->getAllowedForumPmFields($post);
            
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
                                if (is_array($value)) {
                                    // @TODO user service!
                                    $this->propelSetter($post, $value, $setter);
                                } else {
                                    $post->{$setter}($value);
                                }
                            }
                            break;
                        default:
                            throw new \Exception('not implemented `op`');
                    }
                }
            }
            $repo->save($post);
            return $this->handleData($request, $post);
        } catch (PmHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
}
