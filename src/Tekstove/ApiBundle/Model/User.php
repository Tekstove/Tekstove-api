<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\User as BaseUser;
use Tekstove\ApiBundle\Model\Acl\Permission;
use Tekstove\ApiBundle\Model\User\Exception\UserHumanReadableException;

use Propel\Runtime\Connection\ConnectionInterface;

use Tekstove\ApiBundle\Model\User\Pm;
use Tekstove\ApiBundle\Model\User\PmQuery;
use Tekstove\ApiBundle\Model\Lyric;

use Tekstove\ApiBundle\Model\Acl\EditableInterface;
use Tekstove\ApiBundle\Model\Acl\AutoAclSerializableInterface;

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
class User extends BaseUser implements EditableInterface, AutoAclSerializableInterface
{
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    use AclTrait;
    
    public function preSave(ConnectionInterface $con = null)
    {
        if (!$this->validate($this->validator)) {
            $errors = $this->getValidationFailures();
            $exception = new UserHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }
        
        return parent::preSave($con);
    }
    
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
    
    /**
     * @return Acl\PermissionGroup[]
     */
    public function getPermissionGroups()
    {
        $return = [];
        foreach ($this->getPermissionGroupUsers() as $permissionGroup) {
            $return[$permissionGroup->getGroupId()] = $permissionGroup->getPermissionGroup();
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
    
    /**
     * @param Lyric $lyric
     * @return array
     */
    public function getAllowedLyricFields(Lyric $lyric)
    {
        $allowedFields = [];
        $owner = false;
        // $this->getId is check if user is guest
        if ($this->getId() && $lyric->getsendBy() === $this->getId()) {
            $owner = true;
        }
        
        if (!$lyric->getId()) {
            $owner = true;
        }
        
        $permissions = $this->getPermissions();
        
        if ($owner || array_key_exists(Permission::LYRIC_EDIT_BASIC, $permissions)) {
            $allowedFields[] = 'title';
            $allowedFields[] = 'artists';
            $allowedFields[] = 'text';
            $allowedFields[] = 'textBg';
            $allowedFields[] = 'languages';
            $allowedFields[] = 'extraInfo';
            $allowedFields[] = 'videoYoutube';
            $allowedFields[] = 'videoVbox7';
            $allowedFields[] = 'videoMetacafe';
            
            // do not allow delete on new lyric
            if ($lyric->getId()) {
                $allowedFields[] = 'delete';
            }
        }
        
        if ($lyric->getId() && array_key_exists(Permission::LYRIC_EDIT_DELETE, $permissions)) {
            $allowedFields[] = 'delete';
        }
        
        if (array_key_exists(Permission::LYRIC_EDIT_DOWNLOAD, $permissions)) {
            $allowedFields[] = 'download';
        }
        
        if ($lyric->isForbidden()) {
            $hasTextField = array_search('text', $allowedFields);
            if (false !== $hasTextField) {
                unset($allowedFields[$hasTextField]);
            }
        }
        
        return $allowedFields;
    }
    
    public function getAllowedForumPmFields(Pm $pm)
    {
        $return = [];
        if ($pm->getUserTo() === $this->getId()) {
            $return['read'] = 'read';
        }
        
        return $return;
    }

    public function getAllowedUserFields(User $user)
    {
        $return = [];

        if ($user->getId() === $this->getId()) {
            $return[] = 'about';
            $return[] = 'avatar';
        }

        return $return;
    }
    
    /**
     * @return integer
     */
    public function getUnreadPmCount()
    {
        $pmQUery = new PmQuery();
        $pmQUery->filterByUserRelatedByUserTo($this);
        $pmQUery->filterByRead(0);
        $unreadPmCount = $pmQUery->count();
        return $unreadPmCount;
    }

    public function getEditableFields()
    {
        return null;
    }
}
