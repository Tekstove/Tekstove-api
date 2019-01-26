<?php

namespace App\Entity\Lyric;

use App\Entity\Artist\Artist;
use App\Entity\AuthorizationInterface;
use App\Entity\Language;
use App\Entity\Publisher\Publisher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Tekstove\ApiBundle\Model\Acl\AutoAclSerializableInterface;
use Tekstove\ApiBundle\Model\AclTrait;

/**
 * @ORM\Entity()
 */
class Lyric implements AutoAclSerializableInterface
{
    use AclTrait;

    /**
     * No information available
     */
    const AUTHORIZATION_NA = 1;
    const AUTHORIZATION_ALLOWED = 2;
    const AUTHORIZATION_ARTIST_FORBIDDEN = 3;
    const AUTHORIZATION_PUBLISHER_FORBIDDEN = 4;

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
     * @ORM\Column(type="datetime")
     */
    private $textBgAdded;

    /**
     * @ORM\Column(type="integer")
     */
    private $views = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $popularity = 0;

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
     * @ORM\Column(type="boolean")
     */
    private $manualCensor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cacheCensor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="send_by")
     */
    private $sendBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Language")
     */
    private $languages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lyric\ArtistLyric", mappedBy="lyric")
     * @var ArtistLyric[]
     */
    private $artistLyrics;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Publisher\Publisher")
     * @var Publisher[]
     */
    private $publishers;

    /**
     * @ORM\Column(type="string")
     */
    private $cacheTitleShort;

    public function __construct()
    {
        $this->artistLyrics = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->publishers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
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
     * @param mixed $extraInfo
     */
    public function setExtraInfo($extraInfo): void
    {
        $this->extraInfo = $extraInfo;
    }

    /**
     * @return mixed
     */
    public function getViews(): int
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
     * @return mixed
     */
    public function isManualCensored()
    {
        return $this->manualCensor;
    }

    /**
     * @return mixed
     */
    public function isCacheCensored()
    {
        return $this->cacheCensor;
    }

    public function isCensored()
    {
        return $this->isManualCensored() || $this->isCacheCensored();
    }

    /**
     * @return ArtistLyric[]|Collection
     */
    public function getArtistLyrics(): Collection
    {
        return $this->artistLyrics;
    }

    /**
     * @return Artist[]
     */
    public function getArtists(): array
    {
        $artists = [];
        foreach ($this->getArtistLyrics() as $artistLyric) {
            $artists[] = $artistLyric->getArtist();
        }

        return $artists;
    }

    /**
     * @return Language[]|Collection
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    /**
     * @return mixed
     */
    public function getSendBy()
    {
        return $this->sendBy;
    }

    /**
     * @return Publisher[]|ArrayCollection
     */
    public function getPublishers(): Collection
    {
        return $this->publishers;
    }

    /**
     * @return string
     */
    public function getCacheTitleShort(): string
    {
        return $this->cacheTitleShort;
    }

    public function isForbidden(): bool
    {
        $forbidden = true;
        foreach ($this->artistLyrics as $artistLytic) {
            if ($artistLytic->getArtist()->isForbidden()) {
                return true;
            }

            if ($artistLytic->getArtist()->getAuthorization() === AuthorizationInterface::AUTHORIZATION_ALLOWED) {
                $forbidden = false;
            }
        }

        $allowedLyrivs = [
            68126, // official fb page https://www.facebook.com/venelinstefanow/ on 1 Dec 2018
        ];

        if (in_array($this->getId(), $allowedLyrivs)) {
            return false;
        }

        return $forbidden;
    }

    public function getAuthorizationStatus(): int
    {
        $return = self::AUTHORIZATION_NA;
        foreach ($this->artistLyrics as $artistLytic) {
            if ($artistLytic->getArtist()->isForbidden()) {
                return self::AUTHORIZATION_ARTIST_FORBIDDEN;
            }

            if ($artistLytic->getArtist()->getAuthorization() === Artist::AUTHORIZATION_FORBIDDEN) {
                return self::AUTHORIZATION_ARTIST_FORBIDDEN;
            }

            if ($artistLytic->getArtist()->getAuthorization() === AuthorizationInterface::AUTHORIZATION_ALLOWED) {
                $return = self::AUTHORIZATION_ALLOWED;
            }
        }

        foreach ($this->getPublishers() as $publisher) {
            if ($publisher->getAuthorization() === AuthorizationInterface::AUTHORIZATION_ALLOWED) {
                $return = self::AUTHORIZATION_ALLOWED;
            } elseif ($publisher->getAuthorization() === AuthorizationInterface::AUTHORIZATION_FORBIDDEN) {
                return self::AUTHORIZATION_PUBLISHER_FORBIDDEN;
            }
        }

        $allowedLyrivs = [
            68126, // official fb page https://www.facebook.com/venelinstefanow/ on 1 Dec 2018
        ];

        if (in_array($this->getId(), $allowedLyrivs)) {
            return self::AUTHORIZATION_ALLOWED;
        }

        return $return;
    }

    public function addArtist(ArtistLyric $artistLyric)
    {
        $this->artistLyrics->add($artistLyric);
    }

    public function addPublisher(Publisher $publisher)
    {
        $this->publishers->add($publisher);
    }
}
