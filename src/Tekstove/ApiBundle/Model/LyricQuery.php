<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\LyricQuery as BaseLyricQuery;
use Tekstove\ApiBundle\Model\Artist\Map\ArtistLyricTableMap;

/**
 * Skeleton subclass for performing query and update operations on the 'lyric' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class LyricQuery extends BaseLyricQuery
{
    use RepositoryTrait;
    
    public function save(Lyric $lyric)
    {
        $lyric->setEventDispacher($this->eventDispacher);
        $lyric->setValidator($this->validator);
        $lyric->save();
    }
    
    /**
     * @inheritDoc
     * Also allow filtering by artistId
     */
    public function filterByArtist($artist, $comparison = null)
    {
        if (is_numeric($artist)) {
            $artistQuery = new ArtistQuery();
            $artist = $artistQuery->findOneById($artist);
        }
        
        return parent::filterByArtist($artist, $comparison);
    }
    
    public function filterByArtistId($artistIds)
    {
        $artists = new \Propel\Runtime\Collection\ObjectCollection();
        foreach ($artistIds as $artistId) {
            $artist = new Artist();
            $artist->setId($artistId);
            $artists->append($artist);
        }
        
        $this->filterByArtist($artists, \Propel\Runtime\ActiveQuery\Criteria::IN);
        $this->groupById();
        $this->having(
            "COUNT(" .
                ArtistLyricTableMap::COL_LYRIC_ID .
            ") = " . count($artistIds)
        );
        return $this;
    }

    public function filterByUser($user, $comparison = null)
    {
        if (is_numeric($user)) {
            $this->addAnd(Map\LyricTableMap::COL_SEND_BY, $user, \Propel\Runtime\ActiveQuery\Criteria::EQUAL);
            return $this;
        }

        return parent::filterByUser($user, $comparison);
    }
}
