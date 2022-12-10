<?php

declare(strict_types=1);

namespace App\Tests\Service\Mine;

use App\Repository\ShopItemRepository;
use App\Service\Mine\Mine;
use App\Service\Mine\MineScore;
use PHPUnit\Framework\TestCase;
use Redis;

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

        $service = new Mine($redis, $mineScore, $shopItemRepository);

        $actual = $service->click($basePoints, 1);

        $this->assertEquals($expected, $actual);
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
