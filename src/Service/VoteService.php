<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use App\Enum\BetStatus;
use App\Repository\VoteTransactionRepository;
use Doctrine\Persistence\ManagerRegistry;

class VoteService
{
    public function __construct(
        private readonly BetBuilder $betBuilder,
        private readonly VoteTransactionRepository $transactionRepository,
        private readonly ManagerRegistry $doctrine,
    )
    {
    }

    public function createBet(Vote $vote, User $user, int $cash, BetCondition $condition): bool
    {
        $bet = $this->betBuilder->build($vote, $user, $cash, $condition);

        if (!$bet) {
            return false;
        }

        return (bool)$this->transactionRepository->create($bet);
    }

    public function handleBet(Vote $vote): bool
    {
        $winners = $this->transactionRepository->findWinners($vote);
        $losers = $this->transactionRepository->findLosers($vote);

        if (empty($winners) && empty($losers)) {
            return false;
        }

        if (!$losers) {
            $totalBet = (int)($vote->getBet() * 0.5);
            $vote->getUser()->setCash($vote->getUser()->getCash() - $totalBet);
        } else {
            $totalBet = 0;

            foreach ($losers as $loser) {
                $totalBet += $loser->getBet();
                $loser->setStatus(BetStatus::LOSS);
            }
        }

        $totalWinBet = $vote->getBet();
        foreach ($winners as $winner) {
            $totalWinBet += $winner->getBet();
        }

        $win = $this->computeWin($totalBet, $totalWinBet, $vote->getBet());
        $vote->getUser()->setCash($vote->getUser()->getCash() + $win);

        foreach ($winners as $winner) {
            $win = $this->computeWin($totalBet, $totalWinBet, $winner->getBet());
            $winner->setStatus(BetStatus::WIN);
            $winner->setWin($win);
            $winner->getUser()->setCash($winner->getUser()->getCash() + $win);
        }

        $this->doctrine->getManager()->flush();

        return true;
    }

    private function computeWin(int $totalBet, int $totalWinBet, int $bet): int
    {
        return (int) ($totalBet / 100 * ($bet / ($totalWinBet / 100)));
    }
}