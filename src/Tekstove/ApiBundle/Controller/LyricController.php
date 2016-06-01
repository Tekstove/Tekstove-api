<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\LyricQuery;
use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\Artist\ArtistLyric;

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
            
            $caseHelper = new CaseHelper();
            foreach ($allowedFields as $field) {
                $bumpyCase = $caseHelper->bumpyCase($field);
                $camel = $caseHelper->camelCase($field);
                $setter = 'set' . $bumpyCase;
                $value = $request->get($camel);
                if ($setter == 'setArtists') {
                    $artistLyrics = new \Propel\Runtime\Collection\Collection();
                    $artistOrder = 1;
                    foreach ($value as $artistId) {
                        $artistQuery = new \Tekstove\ApiBundle\Model\ArtistQuery();
                        $artist = $artistQuery->findOneById($artistId);
                        if ($artist === null) {
                            throw new \Exception("Can not find artist #{$artistId}");
                        }
                        $artistFound = false;
                        foreach ($lyric->getArtistLyrics() as $artistLyricExisting) {
                            if ($artistLyricExisting->getLyric()->getId() == $lyric->getId()
                                    && $artistLyricExisting->getArtist()->getId() == $artistId) {
                                $artistLyricExisting->setOrder($artistOrder);
                                $artistLyrics->append($artistLyricExisting);
                                $artistFound = true;
                                break;
                            }
                        }

                        if (!$artistFound) {
                            $artistLyric = new ArtistLyric();
                            $artistLyric->setLyric($lyric);
                            $artistLyric->setArtist($artist);
                            $artistLyric->setOrder($artistOrder);
                            $artistLyrics->append($artistLyric);
                        }
                        $artistOrder++;
                    }
                } else {
                    $lyric->{$setter}($value);
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
                                        $artistLyrics = new \Propel\Runtime\Collection\Collection();
                                        $artistOrder = 1;
                                        foreach ($value as $artistId) {
                                            $artistQuery = new \Tekstove\ApiBundle\Model\ArtistQuery();
                                            $artist = $artistQuery->findOneById($artistId);
                                            if ($artist === null) {
                                                throw new \Exception("Can not find artist #{$artistId}");
                                            }
                                            $artistFound = false;
                                            foreach ($lyric->getArtistLyrics() as $artistLyricExisting) {
                                                if ($artistLyricExisting->getLyric()->getId() == $lyric->getId()
                                                        && $artistLyricExisting->getArtist()->getId() == $artistId) {
                                                    $artistLyricExisting->setOrder($artistOrder);
                                                    $artistLyrics->append($artistLyricExisting);
                                                    $artistFound = true;
                                                    break;
                                                }
                                            }
                                            
                                            if (!$artistFound) {
                                                $artistLyric = new ArtistLyric();
                                                $artistLyric->setLyric($lyric);
                                                $artistLyric->setArtist($artist);
                                                $artistLyric->setOrder($artistOrder);
                                                $artistLyrics->append($artistLyric);
                                            }
                                            $artistOrder++;
                                        }
                                        $lyric->setArtistLyrics($artistLyrics);
                                    } else {
                                        throw new \Exception("not implemented");
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
