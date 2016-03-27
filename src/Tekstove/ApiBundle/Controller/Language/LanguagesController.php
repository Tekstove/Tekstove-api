<?php

namespace Tekstove\ApiBundle\Controller\Language;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\LanguageQuery;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class LanguagesController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->getContext()->setGroups(['List']);
        $languagesQuery = new LanguageQuery();
        $languagesQuery->orderById();
        
        $this->setItemsPerPage(999);
        
        return $this->handleData($request, $languagesQuery);
    }
}
