<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\LyricQuery;
use Tekstove\ApiBundle\Model\Lyric;

use Potaka\Helper\Casing\CaseHelper;

use Tekstove\ApiBundle\Model\User;

use Tekstove\ApiBundle\Model\Lyric\Exception\LyricHumanReadableException;

class LyricController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $lyricQuery = new LyricQuery();
        return $this->handleData($request, $lyricQuery);
    }
    
    public function getAction(Request $request, $id)
    {
        $this->applyGroups($request);
        $lyricQuery = new LyricQuery();
        $lyric = $lyricQuery->findOneById($id);
        return $this->handleData($request, $lyric);
    }
    
    public function postAction(Request $request)
    {
        $repo = $this->get('tekstove.lyric.repository');
        $lyric = new \Tekstove\ApiBundle\Model\Lyric();
        $this->getContext()
                ->setGroups(['List']);
        try {
            if ($this->getUser()) {
                $user = $this->getUser();
            } else {
                $user = new User();
            }

            $allowedFields = $user->getAllowedLyricFields($lyric);
            
            $lyricDataJson = $request->getContent();
            $lyricData = json_decode($lyricDataJson, true);
            
            $caseHelper = new CaseHelper();
            foreach ($allowedFields as $field) {
                $bumpyCase = $caseHelper->bumpyCase($field);
                $camel = $caseHelper->camelCase($field);
                $setter = 'set' . $bumpyCase;
                if ($setter == 'setArtists') {
                    if (!isset($lyricData[$camel])) {
                        $lyricData[$camel] = [];
                    }
                    $value = $lyricData[$camel];
                    $lyric->setArtistsIds($value);
                } else {
                    if (!isset($lyricData[$camel])) {
                        $lyricData[$camel] = null;
                    }
                    $value = $lyricData[$camel];
                    // @TODO user service!
                    $this->propelSetter($lyric, $value, $setter);
                }
            }
            $repo->save($lyric);
            return $this->handleData($request, $lyric);
        } catch (LyricHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
    
    public function patchAction(Request $request, $id)
    {
        $this->getContext()
                ->setGroups(['List']);
        
        $repo = $this->get('tekstove.lyric.repository');
        /* @var $repo \Tekstove\ApiBundle\Model\Lyric\LyricRepository */
        $lyricQuery = new LyricQuery();
        /* @var $lyric Lyric */
        $lyric = $lyricQuery->findOneById($id);
        
        // @OTO change to real patch!
        
        try {
            if ($this->getUser()) {
                $user = $this->getUser();
            } else {
                $user = new User();
            }

            $allowedFields = $user->getAllowedLyricFields($lyric);
            
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
                                    if ($field === 'artists') {
                                        $lyric->setArtistsIds($value);
                                    } else {
                                        // @TODO user service!
                                        $this->propelSetter($lyric, $value, $setter);
                                    }
                                } else {
                                    $lyric->{$setter}($value);
                                }
                            }
                            break;
                        default:
                            throw new \Exception('not implemented `op`');
                    }
                }
            }
            $repo->save($lyric);
            return $this->handleData($request, $lyric);
        } catch (LyricHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
}
