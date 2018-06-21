<?php

namespace App\Controller\Lyric;

use App\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Lyric\LyricTopPopularityQuery;

/**
 * TopPopularityController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TopPopularityController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $lyricQuery = new LyricTopPopularityQuery();
        return $this->handleData($request, $lyricQuery);
    }
}
