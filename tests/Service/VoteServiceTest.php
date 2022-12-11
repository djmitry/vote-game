<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Vote;
use App\Entity\VoteTransaction;
use App\Enum\BetCondition;
use App\Repository\VoteTransactionRepository;
use App\Service\BetBuilder;
use App\Service\VoteService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class VoteServiceTest extends TestCase
{
    public function testHandleBet()
    {
        $transactionWin = new VoteTransaction();
        $transactionWin
            ->setBetCondition(BetCondition::LIKE)
            ->setBet(100)
            ->setUser((new User())->setCash(0));

        $transactionLoser = new VoteTransaction();
        $transactionLoser
            ->setBetCondition(BetCondition::DISLIKE)
            ->setBet(200);

        $transactionRepositoryStub = $this->createStub(VoteTransactionRepository::class);
        $transactionRepositoryStub
            ->method('findWinners')
            ->willReturn([$transactionWin]);

        $transactionRepositoryStub
            ->method('findLosers')
            ->willReturn([$transactionLoser]);

        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine
            ->method('getManager')
            ->willReturn($this->createMock(EntityManagerInterface::class));

        $service = new VoteService(
            $this->createMock(BetBuilder::class),
            $transactionRepositoryStub,
            $doctrine
        );

        $vote = new Vote();
        $vote
            ->setBetCondition(BetCondition::LIKE)
            ->setBet(1000)
            ->setUser(new User());

        $actual = $service->handleBet($vote);

        $this->assertTrue($actual);
    }
}
