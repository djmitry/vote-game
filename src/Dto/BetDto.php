<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use App\Enum\BetStatus;

class BetDto
{
    public function __construct(
        private readonly Vote         $vote,
        private readonly User         $user,
        private readonly int          $userCash,
        private readonly int          $bet,
        private readonly BetCondition $condition,
        private readonly BetStatus    $status,
    )
    {
    }

    /**
     * @return Vote
     */
    public function getVote(): Vote
    {
        return $this->vote;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getUserCash(): int
    {
        return $this->userCash;
    }

    /**
     * @return int
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    public function getCondition(): BetCondition
    {
        return $this->condition;
    }

    /**
     * @return BetStatus
     */
    public function getStatus(): BetStatus
    {
        return $this->status;
    }


}