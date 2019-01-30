<?php

namespace App\Entity\Artist;

use App\Entity\AuthorizationInterface;
use App\Entity\AuthorizationTrait;
use App\Entity\Social\FacebookPageTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Artist implements AuthorizationInterface
{
    use AuthorizationTrait;
    use FacebookPageTrait;

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
     * @ORM\Column(type="string")
     */
    private $about;

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
     * @return string|null
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }
}
