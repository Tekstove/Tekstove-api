<?php

namespace App\Controller\Chat;

use App\Controller\TekstoveAbstractController as Controller;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Chat\OnlineQuery;
use Tekstove\ApiBundle\Model\Chat\Online;
use Propel\Runtime\ActiveQuery\Criteria;
use App\HttpFoundation\RequestIdentificator;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class OnlineController extends Controller
{
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        $logger->error("Code is deprecated and will be removed!", ['class' => __CLASS__, 'method' => __METHOD__]);

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

    public function postAction(Request $request, LoggerInterface $logger)
    {
        $logger->error("Code is deprecated and will be removed!", ['class' => __CLASS__, 'method' => __METHOD__]);

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
            $userName = (new RequestIdentificator())->identify($request);
            $anonymousUserQuery = clone $onlineQuery;
            $anonymousUserQuery->filterByUserId(null);
            $anonymousUserQuery->filterByUsername($userName);
            $onlineUser = $anonymousUserQuery->findOne();
            if (!$onlineUser) {
                $onlineUser = new Online();
                $onlineUser->setUsername($userName);
            }
        }

        $onlineUser->setDate(time());
        $onlineUser->save();

        return $this->handleData($request, null);
    }
}
