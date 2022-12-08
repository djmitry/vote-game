<?php

declare(strict_types=1);

namespace App\Tests\Service\Mine;

use App\Service\Mine\MineScore;
use PHPUnit\Framework\TestCase;

class MineScoreTest extends TestCase
{
    /**
     * @dataProvider computeDataProvider
     */
    public function testCompute(int $start, int $end, $expected): void
    {
        $service = new MineScore();

        $actual = $service->compute($start, $end);

        $this->assertEquals($expected, $actual);
    }

    public function computeDataProvider(): array
    {
        return [
            [
                0,
                100,
                5
            ],
            [
                0,
                200,
                4
            ],
            [
                0,
                300,
                3
            ],
        ];
    }
}
