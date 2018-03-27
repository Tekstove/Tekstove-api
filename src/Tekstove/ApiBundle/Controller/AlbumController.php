<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\AlbumQuery;
use Tekstove\ApiBundle\Model\Album;
use Potaka\Helper\Casing\CaseHelper;

/**
 * Description of AlbumController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class AlbumController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $lyricQuery = new AlbumQuery();
        return $this->handleData($request, $lyricQuery);
    }
    
    public function getAction(Request $request, $id)
    {
        $this->applyGroups($request);
        $albumsQuery = new AlbumQuery();
        $artist = $albumsQuery->findOneById($id);
        return $this->handleData(
            $request,
            $artist
        );
    }

    public function postAction(Request $request)
    {
        $this->userMustBeLogged();

        $this->getContext()->setGroups(['List']);

        $postDataJson = $request->getContent();
        $postData = json_decode($postDataJson, true);

        $album = new Album();
        $album->setUser($this->getUser());

        $allowedFields = $this->getUser()->getAllowedAlbumFields($album);
        $caseHelper = new CaseHelper();
        foreach ($allowedFields as $field) {
            $bumpyCase = $caseHelper->bumpyCase($field);
            $camel = $caseHelper->camelCase($field);
            $setter = 'set' . $bumpyCase;
            if ($setter == 'setArtists') {
                if (!isset($postData[$camel])) {
                    $postData[$camel] = [];
                }
                $value = $postData[$camel];
                $album->setArtistsIds($value);
            } elseif ($setter === 'setLyrics') {
                foreach ($postData[$camel] as $artistLyricData) {
                    $albumLyric = new \Tekstove\ApiBundle\Model\AlbumLyric();

                    // @FIXME check when lyric do not exists!

                    $albumLyric->setLyricId(
                        $artistLyricData['lyric'] ?? null
                    );
                    $albumLyric->setName(
                        $artistLyricData['name'] ?? null
                    );
                    $album->addAlbumLyric($albumLyric);
                }
            } else {
                if (!isset($postData[$camel])) {
                    $postData[$camel] = null;
                }
                $value = $postData[$camel];
                // @TODO use service!
                $this->propelSetter($album, $value, $setter);
            }
        }

        try {
            $albumRepo = $this->get('tekstove.album.post.repository');
            $albumRepo->save($album);

            return $this->handleData($request, $album);
        } catch (PostHumanReadableException $e) { // @FIXME ex type!
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
}
