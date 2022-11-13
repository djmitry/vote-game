<?php

namespace App\Service;

use App\Dto\BetDto;
use App\Entity\VoteTransaction;
use App\Enum\BetStatus;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class VoteService
{
    private ObjectManager $em;

    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->em = $doctrine;
    }

    public function prepareBet($vote, \App\Entity\User $user, int $cash): ?BetDto
    {
        if ($cash > $user->getCash()) {
            return null;
        }

        $betDto = new BetDto(
            $vote,
            $user,
            $user->getCash() - $cash,
            $cash,
            BetStatus::BET
        );

        return $betDto;
    }

//    TODO: another strategy, repo save
    public function createBet(BetDto $betDto): void
    {
        $user = $betDto->getUser();
        $user->setCash($betDto->getUserCash());

        $transaction = new VoteTransaction();
        $transaction->setVote($betDto->getVote());
        $transaction->setUser($betDto->getUser());
        $transaction->setBet($betDto->getBet());
        $transaction->setStatus($betDto->getStatus());

        $this->em->persist($transaction);
        $this->em->flush();
    }
}