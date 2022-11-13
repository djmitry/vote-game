<?php

namespace App\Tests\Service;

use App\Dto\BetDto;
use App\Entity\User;
use App\Entity\Vote;
use App\Entity\VoteTransaction;
use App\Enum\BetStatus;
use App\Service\VoteService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class VoteServiceTest extends TestCase
{
    /**
     * @dataProvider betDataProvider
     */
    public function testPrepareBet(User $user, Vote $vote, BetDto $betDto, int $cash): void
    {
        $service = new VoteService($this->createMock(EntityManagerInterface::class));
        $actual = $service->prepareBet($vote, $user, $cash);

        $this->assertEquals($betDto, $actual);
    }

    private function betDataProvider(): array
    {
        $user = new User();
        $user->setCash(1000);

        $vote = new Vote();

        $betDto = new BetDto(
            $vote,
            $user,
            900,
            100,
            BetStatus::BET
        );

        return [
            [
                $user,
                $vote,
                $betDto,
                100,
            ],
        ];
    }

//    TODO:
    public function testCreateBet(): void
    {
        $user = new User();
        $vote = new Vote();

        $betDto = new BetDto(
            $vote,
            $user,
            0,
            0,
            BetStatus::BET
        );

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $service = new VoteService($em);
        $service->createBet($betDto);
    }
}
