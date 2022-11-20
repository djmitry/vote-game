<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class Mine
{
    private const KEY = 'mineClickTime';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function click(int $basePoints): int
    {
        return $basePoints * $this->getRate();
    }

    private function getRate(): int
    {
        $currentTime = time() * 1000;
        $recentTime = $this->requestStack->getSession()->get(self::KEY, $currentTime);
        $this->requestStack->getSession()->set(self::KEY, $currentTime);

        $diff = $currentTime - $recentTime;
        return match(true) {
            $diff <= 100 => 5,
            $diff <= 200 => 4,
            $diff <= 300 => 3,
            $diff <= 400 => 2,
            default => 1,
        };
    }
}