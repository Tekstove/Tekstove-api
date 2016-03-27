<?php

namespace Tekstove\ApiBundle\Controller\Lyric;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CredentialsController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class CredentialsController extends Controller
{
    public function indexAction(Request $request)
    {
        $allowedFields = [
            'title',
            'text',
            'video_youtube',
            'video_vbox7',
            'video_metacafe',
        ];

        if ($this->getUser()) {
            $permissions = $this->getUser()->getPermissions();
            if (array_key_exists('lyric_download', $permissions)) {
                $allowedFields[] = 'download';
            }
        }
        
        $data = [
            'item' => [
                'fields' => $allowedFields,
            ],
        ];
        
        return $this->handleData($request, $data);
    }
}
