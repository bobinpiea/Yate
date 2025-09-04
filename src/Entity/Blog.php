<?php

namespace App\Entity;

use App\Repository\BlogRepository; // Quel fichier utiliser pour les requetes SQL / DQL 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlogRepository::class)] // Cette classe est une entité, il faut passer par le fichier Blogrepository
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide.")]
    #[Assert\Length(
        max: 150,
        maxMessage: "Le titre ne peut pas dépasser 150 caractères."
    )]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "L’article ne peut pas être vide.")]
    #[Assert\Length(
        min: 10,
        minMessage: "L’article doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $article = null;

    #[ORM\Column]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(
        message: "Le format de l’URL n’est pas valide."
    )]
    private ?string $url = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(
        choices: ['expertise', 'deco'],
        message: 'Veuillez sélectionner une des deux catégories.'
    )]
    private ?string $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): static
    {
        $this->article = $article;
        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;
        return $this;
    }

        public function __construct() // Initialiser une valeur par défaut
    {
        $this->dateCreation = new \DateTime();
    }

}

