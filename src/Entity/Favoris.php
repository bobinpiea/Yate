<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Annonce>
     */
    #[ORM\ManyToMany(targetEntity: Annonce::class, inversedBy: 'favoris')]
    private Collection $favoris;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Annonce $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
        }

        return $this;
    }

    public function removeFavori(Annonce $favori): static
    {
        $this->favoris->removeElement($favori);

        return $this;
    }
}
