<?php

namespace App\Enum;

enum BetCondition: int {
    case LIKE = 0;
    case DISLIKE = 1;
    case EQUALS = 2;
}
