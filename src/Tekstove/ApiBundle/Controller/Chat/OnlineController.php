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
            /* @var $online Online */
            if ($online->getUserId()) {
                $onlineUsers[] = $online->getUser();
            } else {
                $anonymousUser = new \Tekstove\ApiBundle\Model\User();
                $anonymousUser->setUsername($online->getUsername());
                $onlineUsers[] = $anonymousUser;
            }
        }

        return $this->handleData($request, ['items' => $onlineUsers]);
    }

    public function postAction(Request $request)
    {
        $this->getContext()
                ->setGroups(['List']);

        $onlineQuery = new OnlineQuery();
        if ($this->getUser()) {
            $onlineUser = $onlineQuery->findOneByUserId($this->getUser()->getId());
            if (empty($onlineUser)) {
                $onlineUser = new Online();
                $onlineUser->setUser($this->getUser());
                $onlineUser->setUsername($this->getUser()->getUsername());
            }
        } else {
            // anonymous user
            $ip = $request->getClientIp();
            $ua = $request->headers->get('user-agent', '');
            $userName = crc32(sha1($ip . $ua));
            $anonymousUserQuery = clone $onlineQuery;
            $anonymousUserQuery->filterByUserId(null);
            $anonymousUserQuery->filterByUsername($userName);
            $onlineUser = $anonymousUserQuery->findOne();
            if (!$onlineUser) {
                $onlineUser = new Online();
            }
            $onlineUser->setUsername($userName);
        }

        $onlineUser->setDate(time());
        $onlineUser->save();

        return $this->handleData($request, null);
    }
}
