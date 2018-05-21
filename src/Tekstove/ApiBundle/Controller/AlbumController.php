<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\AlbumQuery;
use Tekstove\ApiBundle\Model\Album;
use Potaka\Helper\Casing\CaseHelper;

/**
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

        $album = new Album([]);
        $album->setUser($this->getUser());

        $allowedFields = $this->getUser()->getAllowedAlbumFields($album);
        $caseHelper = new CaseHelper();


        $insertData = [];

        foreach ($postData as $key => $value) {
            if (false !== array_search($key, $allowedFields)) {
                $insertData[$key] = $value;
            }
        }

        //@TODO validation will be service
        // @TODO and there will be groups like `insert` and `update`
        $validator = $this->get('validator');
        $constraint = new \Symfony\Component\Validator\Constraints\Collection(
            [
                'name' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                    new \Symfony\Component\Validator\Constraints\Length(
                        [
                            'max' => 5,
                        ]
                    ),
                ],
                'year' => [
                    new \Symfony\Component\Validator\Constraints\Optional(
                        [
                            new \Symfony\Component\Validator\Constraints\Range(
                                [
                                    'min' => 500,
                                    'max' => 3000, // I'm planning refactoring in next 980 years
                                ]
                            ),
                        ]
                    ),
                ],
            ]
        );

        $constraint->allowExtraFields = true;

        $violations = $validator->validate($insertData, $constraint);
        if ($violations->count()) {
            $errors = [];
            foreach ($violations as $error) {
                $errors[] = [
                    'element' => trim($error->getPropertyPath(), '[]'), // @TODO why path is [name]
                    'message' => $error->getMessage(),
                ];
            }

            $view = $this->handleData($request, $errors);
            $view->setStatusCode(400);
            return $view;
        }


        // @validate insert data?

        try {

            foreach ($insertData as $field => $value) {
                $bumpyCase = $caseHelper->bumpyCase($field);
                $setter = 'set' . $bumpyCase;
                if ($field == 'artists') {
                    $album->setArtistsIds($value);
                } elseif ($field === 'lyrics') {
                    foreach ($value as $artistLyricData) {
                        $albumLyric = new \Tekstove\ApiBundle\Model\AlbumLyric();

                        $lyricId = $artistLyricData['lyric'] ?? null;
                        if ($lyricId) {
                            $lyricQuery = new \Tekstove\ApiBundle\Model\LyricQuery();
                            $matchedLyric = $lyricQuery->findOneById($lyricId);
                            if (!$matchedLyric) {
                                $ex = new \Tekstove\ApiBundle\Model\Forum\Post\PostHumanReadableException(); // @FIXME type!
                                $ex->addError('lyrics', 'lyric ' . $lyricId . ' not found!');
                                throw $ex;
                            }
                        }
                        $albumLyric->setLyricId($lyricId);
                        $albumLyric->setName(
                            $artistLyricData['name'] ?? null
                        );
                        $album->addAlbumLyric($albumLyric);
                    }
                } else {
                    $this->propelSetter($album, $value, $setter);
                }
            }

            $albumRepo = $this->get('tekstove.album.post.repository');
            $albumRepo->save($album);

            return $this->handleData($request, $album);
        } catch (\Tekstove\ApiBundle\Model\Forum\Post\PostHumanReadableException $e) { // @FIXME ex type!
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
}
