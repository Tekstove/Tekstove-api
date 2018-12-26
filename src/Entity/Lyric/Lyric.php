<?php

namespace App\Entity\Lyric;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Lyric
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendDate;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lyric\ArtistLyric", mappedBy="lyric")
     * @var ArtistLyric[]
     */
    private $artistLyrics;

    public function __construct()
    {
        $this->artistLyrics = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return striong|null
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @return mixed
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return ArtistLyric[]
     */
    public function getArtistLyrics(): Collection
    {
        return $this->artistLyrics;
    }

    public function isForbidden(): bool
    {
        foreach ($this->artistLyrics as $artistLytic) {
            if ($artistLytic->getArtist()->isForbidden()) {
                return true;
            }
        }

        return false;
    }
}
