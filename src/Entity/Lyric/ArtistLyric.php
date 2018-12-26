<?php

namespace App\Entity\Lyric;

use App\Entity\Artist\Artist;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ArtistLyric
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist\Artist")
     */
    private $artist;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Lyric\Lyric", inversedBy="artistLyrics")
     */
    private $lyric;

    /**
     * @return mixed
     */
    public function getArtist(): Artist
    {
        return $this->artist;
    }
}
