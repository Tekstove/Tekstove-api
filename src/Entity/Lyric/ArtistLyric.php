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
     * @ORM\Column(type="integer")
     */
    private $order;

    /**
     * ArtistLyric constructor.
     * @param $artist
     * @param $lyric
     * @param $order
     */
    public function __construct(Artist $artist, Lyric $lyric, int $order)
    {
        $this->artist = $artist;
        $this->lyric = $lyric;
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getArtist(): Artist
    {
        return $this->artist;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order): void
    {
        $this->order = $order;
    }
}
