<?php

namespace Tekstove\ApiBundle\Model\User;

use Tekstove\ApiBundle\Model\User\Base\Pm as BasePm;

/**
 * Skeleton subclass for representing a row from the 'pm' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Pm extends BasePm
{
    public function getDateTimeTimestamp()
    {
        return $this->getDatetime('U');
    }
}
