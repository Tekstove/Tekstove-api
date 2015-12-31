<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\LyricQuery;

class LyricController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->getContext()
                ->setGroups(['List']);
        $lyricQuery = new LyricQuery();
        return $this->handleData($request, $lyricQuery);
    }
}
