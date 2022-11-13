<?php

namespace App\Enum;

enum BetStatus: int {
    case BET = 0;
    case WIN = 1;
    case LOSS = 2;
}
