<?php

namespace App\Entity\Artist;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Artist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $forbidden;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function isForbidden(): bool
    {
        return $this->forbidden;
    }

    /**
     * @param mixed $forbidden
     */
    public function setForbidden(bool $forbidden)
    {
        $this->forbidden = $forbidden;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
