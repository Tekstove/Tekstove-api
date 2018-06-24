<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\ArtistQuery;
use Potaka\Helper\Casing\CaseHelper;
use Tekstove\ApiBundle\Model\Artist\Exception\ArtistHumanReadableException;

class ArtistController extends TekstoveAbstractController
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $artistQuery = new ArtistQuery();
        return $this->handleData($request, $artistQuery);
    }

    public function getAction(Request $request, $id)
    {
        $this->applyGroups($request);
        $artistQuery = new ArtistQuery();
        $artist = $artistQuery->findOneById($id);

        if (!$artist) {
            throw $this->createNotFoundException("Artist not found");
        }

        return $this->handleData(
            $request,
            $artist
        );
    }

    public function patchAction(Request $request, $id)
    {
        $this->getContext()
                ->setGroups(['List']);

        $repo = $this->get('tekstove.artist.repository');
        /* @var $repo \Tekstove\ApiBundle\Model\ArtistQuery */
        $artist = $repo->findOneById($id);

        try {
            if (!$this->getUser()) {
                throw new \RuntimeException('User must be logged!');
            }
            $user = $this->getUser();

            $allowedFields = $user->getAllowedArtistFields($artist);

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
                                $artist->{$setter}($value);
                            }
                            break;
                        default:
                            throw new \Exception('not implemented `op`');
                    }
                }
            }
            $repo->save($artist);
            return $this->handleData($request, $artist);
        } catch (ArtistHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);
            return $view;
        }
    }
}
