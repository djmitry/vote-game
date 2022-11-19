<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
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

    public function createBet($vote, User $user, int $cash): bool
    {
        $bet = $this->betBuilder->build($vote, $user, $cash);

        if (!$bet) {
            return false;
        }

        return (bool)$this->transactionRepository->create($bet);
    }
}