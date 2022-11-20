<?php

declare(strict_types=1);

namespace App\Service;

use Redis;

class Mine
{
    private const KEY = 'mineClickTime';

    public function __construct(private readonly Redis $redis)
    {
    }

    public function click(int $basePoints, int $userId): int
    {
        return $basePoints * $this->getRate($userId);
    }

    private function getRate(int $userId): int
    {
        $currentTime = time() * 1000;
        $recentTime = $this->redis->get(self::KEY . $userId) ?? $currentTime;
        $this->redis->set(self::KEY, $currentTime);

        $diff = $currentTime - $recentTime;
        return match (true) {
            $diff <= 100 => 5,
            $diff <= 200 => 4,
            $diff <= 300 => 3,
            $diff <= 400 => 2,
            default => 1,
        };
    }
}