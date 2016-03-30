<?php

namespace Tekstove\ApiBundle\Controller\Lyric;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\User;

/**
 * Description of CredentialsController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class CredentialsController extends Controller
{
    public function indexAction(Request $request)
    {
        if ($this->getUser()) {
            $user = $this->getUser();
        } else {
            $user = new User();
        }
        
        $allowedFields = $user->getAllowedLyricFields(new Lyric());
        
        $data = [
            'item' => [
                'fields' => $allowedFields,
            ],
        ];
        
        return $this->handleData($request, $data);
    }
}
