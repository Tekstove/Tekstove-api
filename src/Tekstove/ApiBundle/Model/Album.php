<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\Album as BaseAlbum;

use Tekstove\ApiBundle\Model\Acl\AutoAclSerializableInterface;

/**
 * Skeleton subclass for representing a row from the 'album' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Album extends BaseAlbum implements AutoAclSerializableInterface
{
    use AclTrait;
    
    /**
     * @return AlbumLyric[]
     */
    public function getOrderedAlbumLyrics()
    {
        $return = [];
        $returnUnordered = [];
        foreach ($this->getAlbumLyrics() as $albumLyric) {
            $returnUnordered[] = [
                'order' => $albumLyric->getOrder(),
                'albumLyric' => $albumLyric,
            ];
        }
        
        usort(
            $returnUnordered,
            function ($a, $b) {
                return $a['order'] > $b['order'];
            }
        );
        
        foreach ($returnUnordered as $albumLyricData) {
            $return[] = $albumLyricData['albumLyric'];
        }
        return $return;
    }
    
    /**
     * @return AlbumArtist[]
     */
    public function getOrderedArtists()
    {
        $return = [];
        $returnUnOrdered = [];
        foreach ($this->getAlbumArtists() as $albumArtist) {
            $returnUnOrdered[] = [
                'order' => $albumArtist->getOrder(),
                'artist' => $albumArtist->getArtist()
            ];
        }
        
        usort(
            $returnUnOrdered,
            function ($a, $b) {
                return $a['order'] > $b['order'];
            }
        );
        
        foreach ($returnUnOrdered as $albumArtistData) {
            $return[] = $albumArtistData['artist'];
        }

        return $return;
    }

    /**
     * Update artists to given array of ids.
     * Order is the same as keys in the array
     * @param array $artistsIds
     * @throws \Exception
     */
    public function setArtistsIds(array $artistsIds)
    {
        $albumArtistCollection = new \Propel\Runtime\Collection\Collection();
        $artistOrder = 1;
        foreach ($artistsIds as $artistId) {
            $artistQuery = new \Tekstove\ApiBundle\Model\ArtistQuery();
            $artist = $artistQuery->findOneById($artistId);
            if ($artist === null) {
                throw new \Exception("Can not find artist #{$artistId}");
            }
            $artistFound = false;
            foreach ($this->getAlbumArtists() as $albumArtistExisting) {
                if ($albumArtistExisting->getArtist()->getId() == $artistId) {
                    $albumArtistExisting->setOrder($artistOrder);
                    $albumArtistCollection->append($albumArtistExisting);
                    $artistFound = true;
                    break;
                }
            }

            if (!$artistFound) {
                $artistLyric = new AlbumArtist();
                $artistLyric->setAlbum($this);
                $artistLyric->setArtist($artist);
                $artistLyric->setOrder($artistOrder);
                $albumArtistCollection->append($artistLyric);
            }
            $artistOrder++;
        }
        $this->setAlbumArtists($albumArtistCollection);
    }
}
