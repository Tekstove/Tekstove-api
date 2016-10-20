<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\User;

/**
 * Description of PersonalController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class PersonalController extends Controller
{
    // @WIP @TODO
    public function indexAction(Request $request)
    {
        /*
         * Server
         * - PM
         * - Notifications
         */
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $this->handleData($request, ['test' => 'test']);
    }
}
