<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Dto\BetDto;
use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use App\Enum\VoteStatus;
use App\Service\BetBuilder;
use PHPUnit\Framework\TestCase;

class BetBuilderTest extends TestCase
{
    /**
     * @dataProvider betDataProvider
     */
    public function testBuildBet(User $user, Vote $vote, BetDto $betDto, int $cash, BetCondition $condition): void
    {
        $service = new BetBuilder();
        $actual = $service->build($vote, $user, $cash, $condition);

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
            BetCondition::LIKE,
            VoteStatus::BET,
        );

        return [
            [
                $user,
                $vote,
                $betDto,
                100,
                BetCondition::LIKE,
            ],
        ];
    }
}
