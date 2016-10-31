<?php

namespace Tekstove\ApiBundle\Model\User;

use Tekstove\ApiBundle\Model\User\Base\PmQuery as BasePmQuery;
use Tekstove\ApiBundle\Model\User\Map\PmTableMap;
use Tekstove\ApiBundle\Model\User;

/**
 * Skeleton subclass for performing query and update operations on the 'pm' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PmQuery extends BasePmQuery
{
    /**
     * User should send or receive the PM
     * @param User $user
     * @return $this
     */
    public function filterByUserSenderOrReceiver(User $user)
    {
        $this->condition(
            'userFromMatch',
            PmTableMap::COL_USER_FROM . ' = ?',
            $user->getId()
        )
        ->condition(
            'userToMatch',
            PmTableMap::COL_USER_TO . ' = ?',
            $user->getId()
        )
        ->combine(['userFromMatch', 'userToMatch'], 'OR', 'userToOrFrom')
        ->where(['userToOrFrom']);
        return $this;
    }
    
    public function save(Pm $pm)
    {
        $pm->save();
    }
}
