<?php

namespace Tekstove\ApiBundle\Controller\Chat;

use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Tekstove\ApiBundle\Model\Chat\Message;
use Tekstove\ApiBundle\Model\Chat\MessageQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Description of MessagesController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class MessagesController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $messageQuery = new MessageQuery();

        if (empty($request->get('filter'))) {
            $maxIdQuery = new MessageQuery();
            $maxIdQuery->orderById(Criteria::DESC);
            $lastmessage = $maxIdQuery->findOne();
            $messageQuery->filterById(
                $lastmessage->getId() - 10,
                Criteria::GREATER_THAN
            );
        }

        $messageQuery->orderById(Criteria::ASC);

        $messageQuery->setLimit(10);

        return $this->handleData($request, $messageQuery);
    }

    public function postAction(Request $request)
    {
        $this->getContext()
                ->setGroups(['List']);

        $message = new Message();
        $message->setMessage(uniqid('test_'));

        if ($this->getUser()) {
            $message->setUser($this->getUser());
        }

        $message->save();
        return $this->handleData($request, $message);
    }
}
