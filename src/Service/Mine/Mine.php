<?php

declare(strict_types=1);

namespace App\Service\Mine;

use Redis;

class Mine
{
    private const KEY = 'mineClickTime';

    public function __construct(private readonly Redis $redis, private readonly MineScore $mineScore)
    {
    }

    public function click(int $basePoints, int $userId): int
    {
        return $basePoints * $this->getRate($userId);
    }

    //TODO: modifiers
    private function getRate(int $userId): int
    {
        $currentTime = (int) (microtime(true) * 1000);
        $recentTime = (int) $this->redis->get(self::KEY . $userId) ?? $currentTime;
        $this->redis->set(self::KEY . $userId, (string) $currentTime);

        return $this->mineScore->compute($recentTime, $currentTime);
    }
}