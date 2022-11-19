<?php

declare(strict_types=1);

namespace App\Enum;

enum BetStatus: int {
    case BET = 0;
    case WIN = 1;
    case LOSS = 2;

    public function label(): string
    {
        return match($this) {
            static::BET => 'Bet',
            static::WIN => 'Win',
            static::LOSS => 'Loss',
        };
    }
}
