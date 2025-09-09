<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateMessage = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sujet $Sujet = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $auteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateMessage(): ?\DateTime
    {
        return $this->dateMessage;
    }

    public function setDateMessage(\DateTime $dateMessage): static
    {
        $this->dateMessage = $dateMessage;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getSujet(): ?Sujet
    {
        return $this->Sujet;
    }

    public function setSujet(?Sujet $Sujet): static
    {
        $this->Sujet = $Sujet;

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
}
