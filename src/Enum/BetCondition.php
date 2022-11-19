<?php

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
//        return [
//            self::LIKE => 'Like',
//            self::DISLIKE => 'Dislike',
//            self::EQUALS => 'Equals',
//        ];
    }
}
