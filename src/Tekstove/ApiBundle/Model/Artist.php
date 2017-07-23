<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\Artist as BaseArtist;

/**
 * Skeleton subclass for representing a row from the 'artist' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Artist extends BaseArtist implements Acl\EditableInterface, Acl\AutoAclSerializableInterface
{

    use AclTrait;

    /**
     * @return Album[]
     */
    public function getAlbums()
    {
        $return = [];
        foreach ($this->getAlbumArtists() as $albumArtist) {
            $return[] = $albumArtist->getAlbum();
        }
        return $return;
    }
}
