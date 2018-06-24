<?php

namespace App\Controller\Artist;

use App\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\Artist;
use Tekstove\ApiBundle\Model\ArtistQuery;
use Tekstove\ApiBundle\Model\User;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class CredentialsController extends Controller
{
    public function indexAction(Request $request, $id)
    {
        // there could be default credentials controller :)

        if ($this->getUser()) {
            $user = $this->getUser();
        } else {
            $user = new User();
        }

        if ($id) {
            $artistQuery = new ArtistQuery();
            $artist = $artistQuery->findOneById($id);
        } else {
            $artist = new Artist();
        }

        $allowedFields = $user->getAllowedArtistFields($artist);

        $data = [
            'item' => [
                'fields' => $allowedFields,
            ],
        ];

        return $this->handleData($request, $data);
    }
}
