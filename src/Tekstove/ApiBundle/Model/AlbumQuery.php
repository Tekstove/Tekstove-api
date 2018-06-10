<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\AlbumQuery as BaseAlbumQuery;
use Tekstove\ApiBundle\Model\Album;

/**
 * Skeleton subclass for performing query and update operations on the 'album' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class AlbumQuery extends BaseAlbumQuery
{
    use RepositoryTrait;

    public function save(Album $album)
    {
        $album->save();
    }
}
