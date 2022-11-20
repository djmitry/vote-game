<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Mine;
use PHPUnit\Framework\TestCase;
use Redis;

class MineTest extends TestCase
{
    /**
     * @dataProvider clickDataProvider
     */
    public function testClick(int $basePoints, int $rate, int $time): void
    {
        $redis = $this->createStub(Redis::class);
        $redis->method('get')->willReturn($time);
        $service = new Mine($redis);

        $actual = $service->click($basePoints, 1);

        $this->assertEquals($basePoints * $rate, $actual);
    }

    public function clickDataProvider(): array
    {
        return [
            [
                2,
                1,
                time() * 1000 - 1000,
            ],
            [
                3,
                2,
                time() * 1000 - 400,
            ],
            [
                10,
                3,
                time() * 1000 - 300,
            ],
            [
                12,
                4,
                time() * 1000 - 200,
            ],
            [
                25,
                5,
                time() * 1000 - 100,
            ],
        ];
    }

}
