<?php

namespace Tekstove\ApiBundle\Model\Acl;

use Tekstove\ApiBundle\Model\Acl\Base\Permission as BasePermission;

/**
 * Skeleton subclass for representing a row from the 'permission' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Permission extends BasePermission
{
    const LYRIC_EDIT_DOWNLOAD = 'lyric_download';
    
    const FORUM_VIEW_SECRET = 'forum_view_secret';
}
