<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;

use Tekstove\ApiBundle\Model\LyricQuery;

class LyricController extends Controller
{
    public function indexAction()
    {
        $this->getContext()
                ->setGroups(['List']);
        $lyricQuery = new LyricQuery();
        return $this->handleData($lyricQuery);
    }
}
