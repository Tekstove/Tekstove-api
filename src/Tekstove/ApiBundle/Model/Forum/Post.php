<?php

namespace Tekstove\ApiBundle\Model\Forum;

use Tekstove\ApiBundle\Model\Forum\Base\Post as BasePost;

/**
 * Skeleton subclass for representing a row from the 'forum_post' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Post extends BasePost
{
    public function getDateTimeTimestamp()
    {
        return $this->getDate('U');
    }
}
