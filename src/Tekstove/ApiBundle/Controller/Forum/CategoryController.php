<?php

namespace Tekstove\ApiBundle\Controller\Forum;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\Forum\CategoryQuery;

/**
 * Description of CategoryController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class CategoryController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);
        $categories = new CategoryQuery();
        return $this->handleData($request, $categories);
    }
}
