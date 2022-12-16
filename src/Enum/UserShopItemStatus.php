<?php

declare(strict_types=1);

namespace App\Enum;

enum UserShopItemStatus: int {
    case NOT_ACTIVE = 0;
    case ACTIVE = 1;

    public function label(): string
    {
        return match($this) {
            self::NOT_ACTIVE => 'Not active',
            self::ACTIVE => 'Active',
        };
    }
}
