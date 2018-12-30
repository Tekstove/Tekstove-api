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
     * @ORM\Column(type="string")
     */
    private $textBg;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    /**
     * @ORM\Column(type="integer")
     */
    private $popularity;

    /**
     * @ORM\Column(type="string")
     */
    private $videoYoutube;

    /**
     * @ORM\Column(type="string")
     */
    private $videoVbox7;

    /**
     * @ORM\Column(type="string")
     */
    private $extraInfo;

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

    public function getTextBg()
    {
        return $this->textBg;
    }

    public function getextraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @return mixed
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * @return mixed
     */
    public function getVideoYoutube()
    {
        return $this->videoYoutube;
    }

    /**
     * @return mixed
     */
    public function getVideoVbox7()
    {
        return $this->videoVbox7;
    }

    /**
     * @return ArtistLyric[]|Collection
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
