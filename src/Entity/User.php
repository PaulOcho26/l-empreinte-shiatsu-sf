<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    /** @var Collection<int, Appointment> */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'patient')]
    private Collection $appointments;

    /** @var Collection<int, GiftCard> */
    #[ORM\OneToMany(targetEntity: GiftCard::class, mappedBy: 'purchaser')]
    private Collection $giftCards;

    /** @var Collection<int, Article> */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'author')]
    private Collection $articles;

    /** @var Collection<int, TherapeuticExchange> */
    #[ORM\OneToMany(targetEntity: TherapeuticExchange::class, mappedBy: 'patient')]
    private Collection $therapeuticExchanges;

    /** @var Collection<int, Lexicon> */
    #[ORM\OneToMany(targetEntity: Lexicon::class, mappedBy: 'author')]
    private Collection $lexicons;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->giftCards = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->therapeuticExchanges = new ArrayCollection();
        $this->lexicons = new ArrayCollection();
    }

    // --- SÉCURITÉ ---
    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getUserIdentifier(): string { return (string) $this->email; }
    public function getRoles(): array {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): static { $this->roles = $roles; return $this; }
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }
    public function eraseCredentials(): void {}

    // --- IDENTITÉ ---
    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $firstName): static { $this->firstName = $firstName; return $this; }
    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $lastName): static { $this->lastName = $lastName; return $this; }

    // --- COLLECTIONS (SYNCHRONICITÉ) ---
    /** @return Collection<int, Appointment> */
    public function getAppointments(): Collection { return $this->appointments; }

    /** @return Collection<int, GiftCard> */
    public function getGiftCards(): Collection { return $this->giftCards; }

    /** @return Collection<int, Article> */
    public function getArticles(): Collection { return $this->articles; }

    /** @return Collection<int, TherapeuticExchange> */
    public function getTherapeuticExchanges(): Collection { return $this->therapeuticExchanges; }

    /** @return Collection<int, Lexicon> */
    public function getLexicons(): Collection { return $this->lexicons; }
}