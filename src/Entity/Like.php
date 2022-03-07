<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="`like`")
 */
class Like
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Film::class, inversedBy="likes")
     */
    private $film;

    /**
     * @ORM\ManyToOne(targetEntity=Impression::class, inversedBy="likes")
     */
    private $impression;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this->film;
    }

    public function setFilm(?Film $film): self
    {
        $this->film = $film;

        return $this;
    }

    public function getImpression(): ?Impression
    {
        return $this->impression;
    }

    public function setImpression(?Impression $impression): self
    {
        $this->impression = $impression;

        return $this;
    }
}
