<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;

class VoteTransactionDto
{
    public function __construct(
        private readonly string            $username,
        private readonly string            $voteTitle,
        private readonly int               $voteId,
        private readonly DateTimeImmutable $voteFinishedAt,
        private readonly int               $bet,
        private readonly int               $win,
        private readonly string            $condition,
        private readonly string            $status,
        private readonly DateTimeImmutable $createdAt,
    )
    {
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getVoteTitle(): string
    {
        return $this->voteTitle;
    }

    /**
     * @return int
     */
    public function getVoteId(): int
    {
        return $this->voteId;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getVoteFinishedAt(): DateTimeImmutable
    {
        return $this->voteFinishedAt;
    }

    /**
     * @return int
     */
    public function getBet(): int
    {
        return $this->bet;
    }


    /**
     * @return int
     */
    public function getWin(): int
    {
        return $this->win;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}