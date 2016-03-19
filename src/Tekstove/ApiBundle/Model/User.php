<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\User as BaseUser;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
    /**
     * Return array
     * [permissioName] => permissionValue
     * @return array
     */
    public function getPermissions()
    {
        $return = [];
        foreach ($this->getPermissionGroupUsers() as $permissionGroupUser) {
            $permissionGroup = $permissionGroupUser->getPermissionGroup();
            foreach ($permissionGroup->getPermissionGroupPermissions() as $groupPermission) {
                $permission = $groupPermission->getPermission();
                $return[$permission->getName()] = $permission->getValue();
            }
        }
        return $return;
    }
    
    public function getPermission($name)
    {
        $permissions = $this->getPermissions();
        if (isset($permissions[$name])) {
            return $permissions[$name];
        }
    }
}
