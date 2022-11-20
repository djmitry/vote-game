<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class Mine
{
    private const KEY = 'mineClickTime';

    public function __construct(private readonly Session $session)
    {
    }

    public function click(int $basePoints): int
    {
        return $basePoints * $this->getRate();
    }

    private function getRate(): int
    {
        $currentTime = time() * 1000;
        $recentTime = $this->session->get(self::KEY, $currentTime);
        $this->session->set(self::KEY, $currentTime);

        $diff = $currentTime - $recentTime;
        $rate = match(true) {
            $diff <= 100 => 5,
            $diff <= 200 => 4,
            $diff <= 300 => 3,
            $diff <= 400 => 2,
            default => 1,
        };

        return $rate;
    }
}