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
    const LYRIC_EDIT_DELETE = 'lyric.edit.delete';
    const LYRIC_EDIT_DOWNLOAD = 'lyric.edit.download';
    const LYRIC_EDIT_VIDEO = 'lyric.edit.video';
    const LYRIC_EDIT_BASIC = 'lyric.edit.basic';

    const ARTIST_EDIT = 'artist.edit';
    
    const FORUM_VIEW_SECRET = 'forum.secret.view';

    const CHAT_MESSAGE_VIEW_DETAILS = 'chat.message.details.view';
    const CHAT_MESSAGE_CENSORE = 'chat.censore';
    const CHAT_MESSAGE_BAN = 'chat.ban';
}
