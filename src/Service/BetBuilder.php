<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BetDto;
use App\Entity\User;
use App\Enum\BetCondition;
use App\Enum\BetStatus;

class BetBuilder
{
    public function build($vote, User $user, int $cash, BetCondition $condition): ?BetDto
    {
        if ($cash > $user->getCash()) {
            return null;
        }

        return new BetDto(
            $vote,
            $user,
            $user->getCash() - $cash,
            $cash,
            $condition,
            BetStatus::BET
        );
    }
}