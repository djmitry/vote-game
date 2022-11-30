<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vote::class, orphanRemoval: true)]
    private Collection $votes;

    #[ORM\Column(type: Types::BIGINT)]
    private int $cash = 0;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: VoteTransaction::class, orphanRemoval: true)]
    private Collection $voteTransactions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserShopItem::class, orphanRemoval: true)]
    private Collection $userShopItems;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->voteTransactions = new ArrayCollection();
        $this->userShopItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setUser($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getUser() === $this) {
                $vote->setUser(null);
            }
        }

        return $this;
    }

    public function getCash(): int
    {
        return $this->cash;
    }

    public function setCash(int $cash): self
    {
        $this->cash = $cash;

        return $this;
    }

    /**
     * @return Collection<int, VoteTransaction>
     */
    public function getVoteTransactions(): Collection
    {
        return $this->voteTransactions;
    }

    public function addVoteTransaction(VoteTransaction $voteTransaction): self
    {
        if (!$this->voteTransactions->contains($voteTransaction)) {
            $this->voteTransactions->add($voteTransaction);
            $voteTransaction->setUser($this);
        }

        return $this;
    }

    public function removeVoteTransaction(VoteTransaction $voteTransaction): self
    {
        if ($this->voteTransactions->removeElement($voteTransaction)) {
            // set the owning side to null (unless already changed)
            if ($voteTransaction->getUser() === $this) {
                $voteTransaction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserShopItem>
     */
    public function getUserShopItems(): Collection
    {
        return $this->userShopItems;
    }

    public function addUserShopItem(UserShopItem $userShopItem): self
    {
        if (!$this->userShopItems->contains($userShopItem)) {
            $this->userShopItems->add($userShopItem);
            $userShopItem->setUser($this);
        }

        return $this;
    }

    public function removeUserShopItem(UserShopItem $userShopItem): self
    {
        if ($this->userShopItems->removeElement($userShopItem)) {
            // set the owning side to null (unless already changed)
            if ($userShopItem->getUser() === $this) {
                $userShopItem->setUser(null);
            }
        }

        return $this;
    }
}
