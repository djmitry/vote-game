<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class MineClick extends Event
{

    public function __construct(private readonly User $user, private readonly int $score)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}