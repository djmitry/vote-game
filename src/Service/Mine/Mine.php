<?php

declare(strict_types=1);

namespace App\Service\Mine;

use App\Repository\ShopItemRepository;
use Redis;

class Mine
{
    private const KEY = 'mineClickTime';

    public function __construct(
        private readonly Redis $redis,
        private readonly MineScore $mineScore,
        private readonly ShopItemRepository $shopItemRepository,
    )
    {
    }

    public function click(int $basePoints, int $userId): int
    {
        ['start' => $start, 'end' => $end] = $this->getRange($userId);

        $modifiers = $this->shopItemRepository->findUserActiveItems($userId, 2);

        return $basePoints * $this->mineScore->compute($start, $end, $modifiers);
    }

    private function getRange(int $userId): array
    {
        $currentTime = (int) (microtime(true) * 1000);
        $recentTime = (int) $this->redis->get(self::KEY . $userId) ?? $currentTime;
        $this->redis->set(self::KEY . $userId, (string) $currentTime);

        return [
            'start' => $recentTime,
            'end' => $currentTime,
        ];
    }
}