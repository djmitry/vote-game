<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BetDto;
use App\Entity\User;
use App\Enum\BetStatus;

class BetBuilder
{
    public function build($vote, User $user, int $cash): ?BetDto
    {
        if ($cash > $user->getCash()) {
            return null;
        }

        $betDto = new BetDto(
            $vote,
            $user,
            $user->getCash() - $cash,
            $cash,
            BetStatus::BET
        );

        return $betDto;
    }
}