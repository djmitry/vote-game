<?php

declare(strict_types=1);

namespace App\Service\Mine;

use App\Entity\ShopItem;
use InvalidArgumentException;

class MineScore
{
    public function __construct()
    {
    }

    /**
     * @param ShopItem[] $modifiers
     */
    public function compute(int $start, int $end, array $modifiers): int
    {
        if ($start > $end) {
            throw new InvalidArgumentException('Wrong range.');
        }

        $diff = $end - $start;

        $score = match (true) {
            $diff <= 150 => 5,
            $diff <= 250 => 4,
            $diff <= 300 => 3,
            $diff <= 400 => 2,
            default => 1,
        };

        $modifiedScore = 0;
        foreach ($modifiers as $modifier) {
            $modifiedScore += $score / 100 * $modifier->getValue();
        }
        $score += (int)$modifiedScore;

        return $score;
    }
}