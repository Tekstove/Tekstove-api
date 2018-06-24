<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\AlbumQuery;
use Tekstove\ApiBundle\Model\Album;
use Potaka\Helper\Casing\CaseHelper;
use Tekstove\ApiBundle\TekstoveApiBundle\Model\Album\Exception\AlbumHumanReadableException;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class AlbumController extends TekstoveAbstractController
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

    private function getValidationConstraint($type)
    {
        if ($type === 'new') {
            $constraintsCollection = \Symfony\Component\Validator\Constraints\Required::class;
        } elseif ($type === 'update') {
            $constraintsCollection = \Symfony\Component\Validator\Constraints\Optional::class;
        } else {
            throw new \Exception('no no no');
        }

        $nameValidations = [
            new $constraintsCollection([
                new \Symfony\Component\Validator\Constraints\Type("string"),
                new \Symfony\Component\Validator\Constraints\NotNull(),
                new \Symfony\Component\Validator\Constraints\NotIdenticalTo(''),
                new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 40,
                        'min' => 1,
                ]),
            ]),
        ];

        $yearValidations = [
            new $constraintsCollection([
                new \Symfony\Component\Validator\Constraints\Range([
                    'min' => 500,
                    'max' => 3000, // I'm planning refactoring in next 980 years
                ]),
            ]),
        ];

        $albumLyricValidationsCollection = new \Symfony\Component\Validator\Constraints\Collection([
            'name' => [
                new \Symfony\Component\Validator\Constraints\Length([
                    'min' => 0,
                    'max' => 60,
                ]),
            ],
        ]);

        $albumLyricValidationsCollection->allowExtraFields = true;

        $albumLyricValidations = [
            new \Symfony\Component\Validator\Constraints\Optional([
                new \Symfony\Component\Validator\Constraints\All([
                    $albumLyricValidationsCollection,
                ])
            ])
        ];

        $constraint = new \Symfony\Component\Validator\Constraints\Collection([
            'fields' => [
                'name' => $nameValidations,
                'year' => $yearValidations,
                'lyrics' => $albumLyricValidations,
            ],
        ]);

        return $constraint;
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

        $insertData = [];

        foreach ($postData as $key => $value) {
            if (false !== array_search($key, $allowedFields)) {
                $insertData[$key] = $value;
            }
        }

        $validator = $this->get('validator');
        $constraint = $this->getValidationConstraint('update');

        $constraint->allowExtraFields = true;

        $violations = $validator->validate($insertData, $constraint);
        if ($violations->count()) {
            $errors = [];
            foreach ($violations as $error) {
                $errors[] = [
                    'element' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }

            $view = $this->handleData($request, $errors);
            $view->setStatusCode(400);
            return $view;
        }

        try {
            foreach ($insertData as $field => $value) {
                $bumpyCase = $caseHelper->bumpyCase($field);
                $setter = 'set' . $bumpyCase;
                if ($field == 'artists') {
                    $album->setArtistsIds($value);
                } elseif ($field === 'lyrics') {
                    $order = 1;
                    foreach ($value as $artistLyricData) {
                        $albumLyric = new \Tekstove\ApiBundle\Model\AlbumLyric();

                        $lyricId = $artistLyricData['lyric'] ?? null;
                        if ($lyricId) {
                            $lyricQuery = new \Tekstove\ApiBundle\Model\LyricQuery();
                            $matchedLyric = $lyricQuery->findOneById($lyricId);
                            if (!$matchedLyric) {
                                $ex = new AlbumHumanReadableException();
                                $ex->addError('lyrics', 'lyric ' . $lyricId . ' not found!');
                                throw $ex;
                            }
                        }
                        $albumLyric->setLyricId($lyricId);
                        $albumLyric->setName(
                            $artistLyricData['name'] ?? null
                        );
                        $albumLyric->setOrder($order);
                        $album->addAlbumLyric($albumLyric);
                        $order++;
                    }
                } else {
                    $this->propelSetter($album, $value, $setter);
                }
            }

            $albumRepo = $this->get('tekstove.album.post.repository');
            $albumRepo->save($album);

            return $this->handleData($request, $album);
        } catch (AlbumHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);

            return $view;
        }
    }

    public function patchAction(Request $request, $id)
    {
        $this->getContext()
                ->setGroups(['List']);

        $repo = $this->get('tekstove.album.post.repository');
        /* @var $repo AlbumQuery */

        $album = $repo->findOneById($id);

        try {
            if ($this->getUser()) {
                $user = $this->getUser();
            } else {
                $user = new User();
            }

            $allowedFields = $user->getAllowedAlbumFields($album);

            $caseHelper = new CaseHelper();
            $content = $request->getContent();
            $requestPathData = json_decode($content, true);

            $updateData = [];
            foreach ($allowedFields as $field) {
                foreach ($requestPathData as $path) {
                    switch ($path['op']) {
                        case 'replace':
                            if ($path['path'] === '/' . $field) {
                                $updateData[$field] = $path['value'];
                            }
                    }
                }
            }

            $validator = $this->get('validator');
            $constraint = $this->getValidationConstraint('update');
            $constraint->allowExtraFields = true;

            $violations = $validator->validate($updateData, $constraint, ['Default']);
            if ($violations->count()) {
                $errors = [];
                foreach ($violations as $error) {
                    $errors[] = [
                        'element' => preg_replace('/^\[([^\[]+)\]/', '$1', $error->getPropertyPath()), // we are validating array, path have []
                        'message' => $error->getMessage(),
                    ];
                }

                $view = $this->handleData($request, $errors);
                $view->setStatusCode(400);
                return $view;
            }

            foreach ($updateData as $field => $value) {
                $bumpyCase = $caseHelper->bumpyCase($field);
                $setter = 'set' . $bumpyCase;

                if ($field === 'lyrics') {
                    $album->setAlbumLyrics(new \Propel\Runtime\Collection\Collection([]));
                    $order = 1;
                    foreach ($value as $lyricPathData) {
                        $albumLyric = new \Tekstove\ApiBundle\Model\AlbumLyric();
                        $lyricId = $lyricPathData['lyric'] ?? null;
                        if ($lyricId) {
                            $lyricQuery = new \Tekstove\ApiBundle\Model\LyricQuery();
                            $matchedLyric = $lyricQuery->findOneById($lyricId);
                            if (!$matchedLyric) {
                                $ex = new AlbumHumanReadableException();
                                $ex->addError('lyrics', 'lyric ' . $lyricId . ' not found!');
                                throw $ex;
                            }
                        }
                        $albumLyric->setLyricId($lyricId);
                        $albumLyric->setName(
                            $lyricPathData['name'] ?? null
                        );
                        $albumLyric->setOrder($order);

                        $album->addAlbumLyric($albumLyric);

                        $order++;
                    }
                } elseif ($field === 'artists') {
                    try {
                        $album->setArtistsIds($value);
                    } catch (\Exception $e) {
                        $ex = new AlbumHumanReadableException();
                        $ex->addError('artists', $e->getMessage());
                        throw $ex;
                    }
                } else {
                    $album->{$setter}($value);
                }
            }

            $repo->save($album);

            return $this->handleData($request, $album);
        } catch (AlbumHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);

            return $view;
        }
    }
}
