<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserZeroHp;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserZeroHpSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function onUserZeroHp(UserZeroHp $event): void
    {
        $user = $event->getUser();
        $user->setRoles(['ROLE_GAME_OVER']);
        $this->userRepository->save($event->getUser(), true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserZeroHp::class => 'onUserZeroHp',
        ];
    }
}
