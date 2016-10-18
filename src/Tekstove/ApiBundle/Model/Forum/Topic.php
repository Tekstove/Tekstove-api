<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Tekstove\ApiBundle\Model\Forum\Base\Topic as BaseTopic;

/**
 * Skeleton subclass for representing a row from the 'forum_topic' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Topic extends BaseTopic
{
    public function getLatestPost()
    {
        $postQuery = new PostQuery();
        $postQuery->filterByTopic($this);
        $postQuery->orderById();
        $post = $postQuery->findOne();
        return $post;
    }
}
