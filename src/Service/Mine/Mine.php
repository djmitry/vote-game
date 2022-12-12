<?php

declare(strict_types=1);

namespace App\Service\Mine;

use App\Event\MineClick;
use App\Repository\ShopItemRepository;
use App\Repository\UserRepository;
use Redis;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Mine
{
    private const KEY = 'mineClickTime';

    public function __construct(
        private readonly Redis $redis,
        private readonly MineScore $mineScore,
        private readonly ShopItemRepository $shopItemRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UserRepository $userRepository
    )
    {
    }

    public function click(int $basePoints, int $userId): int
    {
        $user = $this->userRepository->find($userId);

        ['start' => $start, 'end' => $end] = $this->getRange($user->getId());
        $modifiers = $this->shopItemRepository->findUserActiveItems($user, 2);
        $score = $basePoints * $this->mineScore->compute($start, $end, $modifiers);

        $this->eventDispatcher->dispatch(new MineClick($user, $score));

        return $score;
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