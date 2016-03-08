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
        // @TODO remove mock and add real code
        $data = [
            'item' => [
                'fields' => [
                    'title',
                    'text',
                ],
            ]
        ];
        
        return $this->handleData($request, $data);
    }
}