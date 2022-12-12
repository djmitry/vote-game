<?php

declare(strict_types=1);

namespace App\Tests\Service\Mine;

use App\Entity\User;
use App\Repository\ShopItemRepository;
use App\Repository\UserRepository;
use App\Service\Mine\Mine;
use App\Service\Mine\MineScore;
use PHPUnit\Framework\TestCase;
use Redis;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MineTest extends TestCase
{
    /**
     * @dataProvider clickDataProvider
     */
    public function testClick(int $basePoints, int $expected): void
    {
        $redis = $this->createStub(Redis::class);
        $mineScore = $this->createStub(MineScore::class);
        $mineScore
            ->method('compute')
            ->willReturn(1);
        $shopItemRepository = $this->createStub(ShopItemRepository::class);

        $user = $this->createStub(User::class);
        $user
            ->method('getCurrentHp')
            ->willReturn(100);
        $user
            ->method('getId')
            ->willReturn(1);

        $userRepository = $this->createStub(UserRepository::class);
        $userRepository
            ->method('find')
            ->willReturn($user);

        $service = new Mine(
            $redis,
            $mineScore,
            $shopItemRepository,
            $this->createMock(EventDispatcherInterface::class),
            $userRepository
        );

        $actual = $service->click($basePoints, $user->getId());

        $this->assertEquals($expected, $actual);
        //TODO: Event text
        //$this->assertEquals(99, $user->getCurrentHp());
    }

    public function clickDataProvider(): array
    {
        return [
            [
                1,
                1,
            ],
            [
                2,
                2,
            ],
        ];
    }
}
