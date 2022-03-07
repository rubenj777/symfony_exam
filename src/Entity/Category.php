<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Film::class, mappedBy="category", orphanRemoval=true)
     */
    private $film;

    public function __construct()
    {
        $this->film = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Film>
     */
    public function getFilm(): Collection
    {
        return $this->film;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->film->contains($film)) {
            $this->film[] = $film;
            $film->setCategory($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): self
    {
        if ($this->film->removeElement($film)) {
            // set the owning side to null (unless already changed)
            if ($film->getCategory() === $this) {
                $film->setCategory(null);
            }
        }

        return $this;
    }
}
