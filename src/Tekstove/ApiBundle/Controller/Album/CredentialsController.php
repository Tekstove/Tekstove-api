<?php

namespace Tekstove\ApiBundle\Controller\Album;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\Album;
use Tekstove\ApiBundle\Model\AlbumQuery;
use Tekstove\ApiBundle\Model\User;

/**
 * Description of CredentialsController
 *
 * @author potaka
 */
class CredentialsController extends Controller
{
    public function indexAction(Request $request, $id)
    {
        if ($this->getUser()) {
            $user = $this->getUser();
        } else {
            $user = new User();
        }

        if ($id) {
            $albumQuery = new AlbumQuery();
            $album = $albumQuery->findOneById($id);
        } else {
            $album = new Album();
        }

        $allowedFields = $user->getAllowedAlbumFields($album);

        $data = [
            'item' => [
                'fields' => $allowedFields,
            ],
        ];

        return $this->handleData($request, $data);
    }
}
