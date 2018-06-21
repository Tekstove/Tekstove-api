<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of userCredentialsController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */


class UserCredentialsController extends TekstoveAbstractController
{
    /**
     * @Template();
     */
    public function indexAction(Request $request)
    {
        $userQuery = new \Tekstove\ApiBundle\Model\UserQuery();

        $this->getContext()
                ->setGroups(['Credentials']);

        return $this->handleData($request, $userQuery);
    }

    /**
     * @Template();
     */
    public function getAction(Request $request, $id)
    {
        $userQuery = new \Tekstove\ApiBundle\Model\UserQuery();
        $userQuery->filterById($id);

        $this->getContext()
                ->setGroups(['Credentials']);

        return $this->handleData($request, $userQuery);
    }
}
