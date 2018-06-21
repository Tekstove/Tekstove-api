<?php

namespace App\Controller\Lyric;

use App\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\LyricQuery;
use Tekstove\ApiBundle\Model\User;

/**
 * Handle credentials for new items
 *
 * @author po_taka <angel.koilov@gmail.com>
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
            $lyricQuery = new LyricQuery();
            $lyric = $lyricQuery->findOneById($id);
        } else {
            $lyric = new Lyric();
        }

        $allowedFields = $user->getAllowedLyricFields($lyric);

        $data = [
            'item' => [
                'fields' => $allowedFields,
            ],
        ];

        return $this->handleData($request, $data);
    }
}
