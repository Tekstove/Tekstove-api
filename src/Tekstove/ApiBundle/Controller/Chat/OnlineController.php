<?php

namespace Tekstove\ApiBundle\Controller\Chat;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Chat\OnlineQuery;
use Tekstove\ApiBundle\Model\Chat\Online;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Description of Online
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class OnlineController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);

        $onlineQuery = new OnlineQuery();
        $onlineQuery->filterByDate(time() - 2 * 60, Criteria::GREATER_EQUAL);

        $onlineUsers = [];

        $onlines = $onlineQuery->find();

        foreach ($onlines as $online) {
            $onlineUsers[] = $online->getUser();
        }

        return $this->handleData($request, ['items' => $onlineUsers]);
    }

    public function postAction(Request $request)
    {
        $this->getContext()
                ->setGroups(['List']);

        if ($this->getUser()) {
            $onlineQuery = new OnlineQuery();
            $onlineUser = $onlineQuery->findOneByUserId($this->getUser()->getId());
            if (empty($onlineUser)) {
                $onlineUser = new Online();
                $onlineUser->setUser($this->getUser());
                $onlineUser->setUsername($this->getUser()->getUsername());
            }

            $onlineUser->setDate(time());
            $onlineUser->save();
        }

        return $this->handleData($request, null);
    }
}
