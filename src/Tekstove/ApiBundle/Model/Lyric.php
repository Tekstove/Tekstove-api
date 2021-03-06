<?php

namespace Tekstove\ApiBundle\Model;

use Propel\Runtime\Connection\ConnectionInterface;
use Tekstove\ApiBundle\Model\Base\Lyric as BaseLyric;
use Tekstove\ApiBundle\Model\Artist\ArtistLyric;

use Tekstove\ApiBundle\EventDispatcher\EventDispacher;
use Tekstove\ApiBundle\EventDispatcher\Lyric\LyricEvent;
use Tekstove\ApiBundle\Model\Acl\AutoAclSerializableInterface;

use Tekstove\ApiBundle\Model\Lyric\Exception\LyricHumanReadableException;

/**
 * Skeleton subclass for representing a row from the 'lyric' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Lyric extends BaseLyric implements AutoAclSerializableInterface
{
    use AclTrait;
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    use \Tekstove\ApiBundle\EventDispatcher\EventDispatcherAwareTrait;
    
    public function preSave(ConnectionInterface $con = null)
    {
        if (!$this->validate($this->getValidator())) {
            $errors = $this->getValidationFailures();
            $exception = new LyricHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }
        
        $this->notifyPreSave($this);
        
        return parent::preSave($con);
    }
    
    private function notifyPreSave(Lyric $lyric)
    {
        $event = new LyricEvent($lyric);
        $this->getEventDispacher()->dispatch('tekstove.lyric.save', $event);
    }
    
    /**
     *
     * @return array
     */
    public function getOrderedArtists()
    {
        $return = [];
        foreach ($this->getArtistLyrics() as $artistLyric) {
            $artist = $artistLyric->getArtist();
            $return[] = [
                'id' => $artist->getId(),
                'name' => $artist->getName(),
                'order' => $artistLyric->getOrder(),
            ];
        }
        
        uasort(
            $return,
            function ($a, $b) {
                return $a['order'] > $b['order'];
            }
        );
        
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
        $artistLyrics = new \Propel\Runtime\Collection\Collection();
        $artistOrder = 1;
        foreach ($artistsIds as $artistId) {
            $artistQuery = new \Tekstove\ApiBundle\Model\ArtistQuery();
            $artist = $artistQuery->findOneById($artistId);
            if ($artist === null) {
                throw new \Exception("Can not find artist #{$artistId}");
            }
            $artistFound = false;
            foreach ($this->getArtistLyrics() as $artistLyricExisting) {
                if ($artistLyricExisting->getArtist()->getId() == $artistId) {
                    $artistLyricExisting->setOrder($artistOrder);
                    $artistLyrics->append($artistLyricExisting);
                    $artistFound = true;
                    break;
                }
            }

            if (!$artistFound) {
                $artistLyric = new ArtistLyric();
                $artistLyric->setLyric($this);
                $artistLyric->setArtist($artist);
                $artistLyric->setOrder($artistOrder);
                $artistLyrics->append($artistLyric);
            }
            $artistOrder++;
        }
        $this->setArtistLyrics($artistLyrics);
    }
    
    /**
     * check if some artist do not allow lyrics listing
     * @return bool
     */
    public function isForbidden()
    {
        if ($this->getForbidden()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * check if some artist do not allow lyrics listing
     * @return Artist|bool
     */
    public function getForbidden()
    {
        foreach ($this->getArtists() as $artist) {
            if ($artist->getForbidden()) {
                return $artist;
            }
        }
        
        return false;
    }
}
