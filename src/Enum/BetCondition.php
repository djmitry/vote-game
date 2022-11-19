<?php

declare(strict_types=1);

namespace App\Enum;

enum BetCondition: int {
    case LIKE = 0;
    case DISLIKE = 1;
    case EQUALS = 2;

    public static function labels(): array
    {
        $result = [];
        foreach (BetCondition::cases() as $case) {
            $result[$case->name] = $case->value;
        }
        return $result;
    }

    public function label(): string
    {
        return match($this) {
            static::LIKE => 'Like',
            static::DISLIKE => 'Dislike',
            static::EQUALS => 'Equals',
        };
    }
}
