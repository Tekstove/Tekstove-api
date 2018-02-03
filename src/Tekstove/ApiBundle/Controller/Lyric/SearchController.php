<?php

namespace Tekstove\ApiBundle\Controller\Lyric;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController;

use Symfony\Component\HttpFoundation\Request;
use Propel\Runtime\ActiveQuery\Criteria;

class SearchController extends TekstoveAbstractController
{
    public function indexAction(Request $request)
    {
        $this->applyGroups($request);

        $title = $request->get('title');
        $artistName = $request->get('artistName');
        $artistIds = $request->get('artistIds');
        $text = $request->get('text');

        $query = $this->sqlSearch($title, $text, $artistIds, $artistName);
        $paginatedQuery = $this->propelQueryToPagination($request, $query);
        $arrayData = $this->paginationToArray($paginatedQuery);

        $view = $this->view($arrayData, 200);
        $view->setSerializationContext($this->getContext());

        return $view;
    }

    private function sqlSearch($title, $text, $artistIds, $artistName)
    {
        $query = new \Tekstove\ApiBundle\Model\LyricQuery();
        if ($title) {
            $query->filterByTitle(
                '%' . $title . '%',
                Criteria::LIKE
            );
        }

        if ($text) {
            $criterion = new \Tekstove\ApiBundle\Propel\Runtime\ActiveQuery\Criterion\FullTextCriterion(
                $query,
                \Tekstove\ApiBundle\Model\Map\LyricTableMap::COL_TEXT,
                $text
            );

            $criterionName = uniqid();
            $query->addCond($criterionName, $criterion);

            $query->combine([$criterionName]);
        }

        if ($artistIds) {
            $query->filterByArtistId($artistIds);
        }

        return $query;
    }
}
