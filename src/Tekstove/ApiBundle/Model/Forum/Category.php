<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Tekstove\ApiBundle\Model\Forum\Base\Category as BaseCategory;

/**
 * Skeleton subclass for representing a row from the 'forum_category' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Category extends BaseCategory
{
    public function getLastTopic()
    {
        $categoryId = $this->getId();
        $topicQuery = new TopicQuery();
        $topicQuery->addAnd('forum_category_id', $categoryId, \Propel\Runtime\ActiveQuery\Criteria::EQUAL);
        $topicQuery->orderBy('id', \Propel\Runtime\ActiveQuery\Criteria::DESC);
        $topic = $topicQuery->findOne();
        return $topic;
    }
}
