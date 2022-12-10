<?php

declare(strict_types=1);

namespace App\Tests\Service\Mine;

use App\Entity\ShopItem;
use App\Service\Mine\MineScore;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MineScoreTest extends TestCase
{
    /**
     * @dataProvider computeDataProvider
     */
    public function testCompute(int $start, int $end, int $expected, array $modifiers): void
    {
        $service = new MineScore();

        $actual = $service->compute($start, $end, $modifiers);

        $this->assertEquals($expected, $actual);
    }

    public function computeDataProvider(): array
    {
        return [
            [
                0,
                100,
                5,
                [],
            ],
            [
                0,
                100,
                10,
                [
                    (new ShopItem())->setType(1)->setValue(100),
                ],
            ],
            [
                0,
                200,
                4,
                [],
            ],
            [
                0,
                300,
                3,
                [],
            ],
        ];
    }

    /**
     * @dataProvider computeRangeExceptionDataProvider
     */
    public function testComputeRangeException(int $start, int $end): void
    {
        $this->expectException(InvalidArgumentException::class);

        $service = new MineScore();

        $service->compute($start, $end, []);
    }

    public function computeRangeExceptionDataProvider(): array
    {
        return [
            [
                101,
                100,
            ],
        ];
    }
}
