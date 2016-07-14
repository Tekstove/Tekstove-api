<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of RegisterController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class RegisterController extends TekstoveAbstractController
{
    public function indexAction(Request $request)
    {
        $recaptchaKey = $this->container->getParameter('tekstove_api.recaptcha.key');
        
        $request->request->set('groups', ['unused']);
        $returnData = [
            'item' => [
                'recaptcha' => [
                    'key' => $recaptchaKey,
                ]
            ],
        ];
        return $this->handleData($request, $returnData);
    }
}
