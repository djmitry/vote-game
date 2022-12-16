<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\BetCondition;
use App\Enum\VoteStatus;
use App\Repository\VoteTransactionRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteTransactionRepository::class)]
class VoteTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'voteTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vote $vote = null;

    #[ORM\ManyToOne(inversedBy: 'voteTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::BIGINT)]
    private int $bet;

    #[ORM\Column(type: Types::BIGINT)]
    private int $win = 0;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $status;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $betCondition = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVote(): ?Vote
    {
        return $this->vote;
    }

    public function setVote(?Vote $vote): self
    {
        $this->vote = $vote;

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

    public function getBet(): int
    {
        return $this->bet;
    }

    public function setBet(int $bet): self
    {
        $this->bet = $bet;

        return $this;
    }

    public function getWin(): int
    {
        return $this->win;
    }

    public function setWin(int $win): self
    {
        $this->win = $win;

        return $this;
    }

    public function getStatus(): VoteStatus
    {
        return VoteStatus::from($this->status);
    }

    public function setStatus(VoteStatus $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBetCondition(): BetCondition
    {
        return BetCondition::from($this->betCondition);
    }

    public function setBetCondition(BetCondition $betCondition): self
    {
        $this->betCondition = $betCondition->value;

        return $this;
    }
}
