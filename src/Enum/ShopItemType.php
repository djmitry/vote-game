<?php

declare(strict_types=1);

namespace App\Enum;

enum ShopItemType: int
{
    case HP = 1;
    case MINE = 2;
    case WIN = 3;

    public function label(): string
    {
        return match ($this) {
            self::HP => 'HP',
            self::MINE => 'Mine',
            self::WIN => 'Win',
        };
    }
}
