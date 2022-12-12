<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\MineClick;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MineClickSubscriberSubscriber implements EventSubscriberInterface
{
    private const DECREASE_POINTS = 1;

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function onMineClick(MineClick $event): void
    {
        $user = $event->getUser();
        $user->setCash($user->getCash() + $event->getScore());
        $user->setCurrentHp($user->getCurrentHp() - self::DECREASE_POINTS);

        $this->userRepository->save($event->getUser(), true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MineClick::class => 'onMineClick',
        ];
    }
}
