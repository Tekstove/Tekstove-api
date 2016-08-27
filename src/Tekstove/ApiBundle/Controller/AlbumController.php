<?php

namespace Tekstove\ApiBundle\Controller;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\AlbumQuery;

/**
 * Description of AlbumController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class AlbumController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $lyricQuery = new AlbumQuery();
        return $this->handleData($request, $lyricQuery);
    }
}
