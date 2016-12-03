<?php

namespace Tekstove\ApiBundle\Controller\Chat;

use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
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
        $messageQuery->orderById(Criteria::DESC);
        $messageQuery->setLimit(5);

        return $this->handleData($request, $messageQuery);
    }
}
