<?php

namespace App\Entity;

use App\Repository\ImpressionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImpressionRepository::class)
 */
class Impression
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Film::class, inversedBy="impressions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $film;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="impressions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="impression")
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setImpression($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getImpression() === $this) {
                $like->setImpression(null);
            }
        }

        return $this;
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function isLikedBy(User $user = null): bool
    {
        foreach ($this->likes as $like) {
            if($user == $like->getUser()) {
                return true;
            }
        }
        return false;
    }
}
