<?php

namespace App\Entity\Lyric;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="lyric_redirect")
 */
class Redirect
{
    /**
     * @ORM\Id
     * @ORM\Column()
     */
    private $deletedId;

    /**
     * @ORM\Id
     * @ORM\Column()
     */
    private $redirectId;

    /**
     * @return int
     */
    public function getRedirectId(): int
    {
        return $this->redirectId;
    }
}
