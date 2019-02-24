<?php

namespace App\Entity\Chat;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="chat")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idOverride;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="user_id")
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $message;

    /**
     * @ORM\Column(type="string")
     */
    private $messageHtml;

    /**
     * @ORM\Column(type="string")
     */
    private $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOverride(): ?int
    {
        return $this->idOverride;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function getMessageHtml(): ?string
    {
        return $this->messageHtml;
    }

    public function setMessageHtml(string $messageHtml)
    {
        $this->messageHtml = $messageHtml;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }
}
