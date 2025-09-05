<?php

namespace App\Entity; // La class User appartientà l'espace de nom App\Entity

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection; // On importe le Repository lié à cette Entité
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // Importation pour utiliser les annotations de Doctrine
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface; // Reconnaissance de cette classe comme un utilisateur
use Symfony\Component\Security\Core\User\UserInterface; // Pour l'authentification du mdp
use Symfony\Component\Validator\Constraints as Assert; // Pour la mise en place des contraintes

#[ORM\Entity(repositoryClass: UserRepository::class)] // Relie cette class à Doctrine pour l'enregistrer en bdd
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')] // Email unique afin de ne pas avoir de doublon
class User implements UserInterface, PasswordAuthenticatedUserInterface // Déclaration de la class et ces 2 implémentations (Contrats)
{
    #[ORM\Id] // clé primaire
    #[ORM\GeneratedValue] // Auto-incrémente
    #[ORM\Column] // indique que cette propriété est une colonne en BDD
    private ?int $id = null; // Valeur initiale null

    #[Assert\Email(
        message: "Merci de renseigner une adresse email valide"
    )]
    #[Assert\NotBlank(
        message: "L'email est obligatoire"
    )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     * Recommandation de la CNIL 
     * un mot de passe puissant doit contenir 
     * au moins 12 caractères dont des lettres minuscules, des lettres majuscules et des caractères spéciaux
     */

    #[Assert\NotCompromisedPassword(
        message: "Ce mot de passe a été compromis dans une fuite de données. Veuillez en choisir un autre."
    )]
    #[Assert\NotBlank(
        message: "Le mot de passe est obligatoire."
    )]
    #[Assert\Length(
        min: 12,
        minMessage: "Le mot de passe doit contenir au moins 12 caractères."
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).+$/',
        message: "Le mot de passe doit contenir au moins une minuscule, une majuscule et un caractère spécial."
    )]
    #[ORM\Column]
    //  #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_STRONG)] A Utiliser ou pas ? 
    private ?string $password = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pseudo = null;

    /**
     * @var Collection<int, Blog>
     */
    #[ORM\OneToMany(targetEntity: Blog::class, mappedBy: 'auteur')]
    private Collection $blogs;

    public function __construct()
    {
        $this->blogs = new ArrayCollection();
    }

    // Getter pour récupérer lid de l'utilisateur (Clé primaire)
    public function getId(): ?int
    {
        return $this->id;
    }
    // Getter pour lire l'email actuel
    public function getEmail(): ?string
    {
        return $this->email;
    }
    // Setter pour modifier l'email ( reçoit la nouvelle valeur en paramètre)
    // Retourne l'objet lui même ($this) pour mettre les appels en chaine 
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array // Sérialisation
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): static
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setAuteur($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): static
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getAuteur() === $this) {
                $blog->setAuteur(null);
            }
        }

        return $this;
    }
}
