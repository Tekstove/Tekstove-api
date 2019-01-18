<?php

use App\Entity\Artist\Artist;
use App\Entity\Lyric\ArtistLyric;
use App\Entity\Lyric\Lyric;

class LyricTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAuthorizationStatusNA()
    {
        $lyric = new Lyric();
        $artist = new Artist();
        $artist->setForbidden(false);
        $artist->setAuthorization(Artist::AUTHORIZATION_NA);

        $artistLyric = new ArtistLyric($artist, $lyric, 1);

        $lyric->addArtist($artistLyric);

        $this->assertSame(Lyric::AUTHORIZATION_NA, $lyric->getAuthorizationStatus());
    }

    public function testGetAuthorizationStatusForbidden()
    {
        $lyric = new Lyric();
        $artist = new Artist();
        $artist->setForbidden(false);
        $artist->setAuthorization(Artist::AUTHORIZATION_FORBIDDEN);

        $artistLyric = new ArtistLyric($artist, $lyric, 1);

        $lyric->addArtist($artistLyric);

        $this->assertSame(Lyric::AUTHORIZATION_ARTIST_FORBIDDEN, $lyric->getAuthorizationStatus());
    }

    /**
     * This should be removed when we remove forbidden field
     */
    public function testGetAuthorizationStatusForbiddenArtist()
    {
        $lyric = new Lyric();
        $artist = new Artist();
        $artist->setForbidden(true);

        $artistLyric = new ArtistLyric($artist, $lyric, 1);

        $lyric->addArtist($artistLyric);

        $this->assertSame(Lyric::AUTHORIZATION_ARTIST_FORBIDDEN, $lyric->getAuthorizationStatus());
    }

    public function testGetAuthorizationStatusAllowed()
    {
        $lyric = new Lyric();
        $artist = new Artist();
        $artist->setForbidden(false);
        $artist->setAuthorization(Artist::AUTHORIZATION_ALLOWED);

        $artistLyric = new ArtistLyric($artist, $lyric, 1);

        $lyric->addArtist($artistLyric);

        $this->assertSame(Lyric::AUTHORIZATION_ALLOWED, $lyric->getAuthorizationStatus());
    }
}