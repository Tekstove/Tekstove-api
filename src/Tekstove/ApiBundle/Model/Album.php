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
}
