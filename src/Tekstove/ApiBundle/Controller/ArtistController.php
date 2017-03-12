<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\ArtistQuery;

class ArtistController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $artistQuery = new ArtistQuery();
        return $this->handleData($request, $artistQuery);
    }
    
    public function getAction(Request $request, $id)
    {
        $this->applyGroups($request);
        $artistQuery = new ArtistQuery();
        $artist = $artistQuery->findOneById($id);
        return $this->handleData(
            $request,
            $artist
        );
    }
}
