<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use App\Repository\VoteTransactionRepository;

class VoteService
{
    private BetBuilder $betBuilder;
    private VoteTransactionRepository $transactionRepository;

    public function __construct(BetBuilder $betBuilder, VoteTransactionRepository $transactionRepository)
    {
        $this->betBuilder = $betBuilder;
        $this->transactionRepository = $transactionRepository;
    }

    public function createBet(Vote $vote, User $user, int $cash, BetCondition $condition): bool
    {
        $bet = $this->betBuilder->build($vote, $user, $cash, $condition);

        if (!$bet) {
            return false;
        }

        return (bool)$this->transactionRepository->create($bet);
    }
}