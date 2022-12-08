<?php

declare(strict_types=1);

namespace App\Service\Mine;

class MineScore
{
    public function __construct()
    {
    }

    public function compute(int $start, int $end): int
    {
        $diff = $end - $start;

        return match (true) {
            $diff <= 150 => 5,
            $diff <= 250 => 4,
            $diff <= 300 => 3,
            $diff <= 400 => 2,
            default => 1,
        };
    }
}