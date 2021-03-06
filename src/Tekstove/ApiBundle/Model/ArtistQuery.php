<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\ArtistQuery as BaseArtistQuery;
use Tekstove\ApiBundle\Model\Artist;

/**
 * Skeleton subclass for performing query and update operations on the 'artist' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ArtistQuery extends BaseArtistQuery
{
    use RepositoryTrait;

    public function save(Artist $artist)
    {
        $artist->setEventDispacher($this->eventDispacher);
        $artist->setValidator($this->validator);
        $artist->save();
    }
}
