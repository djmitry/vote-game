<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\MineClick;
use App\Repository\UserRepository;
use App\Service\PushToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MineClickSubscriber implements EventSubscriberInterface
{
    private const DECREASE_POINTS = 1;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly HubInterface $hub,
        private readonly PushToken $pushToken,
    )
    {
    }

    public function onMineClick(MineClick $event): void
    {
        $user = $event->getUser();
        $user->setCash($user->getCash() + $event->getScore());
        $user->setCurrentHp($user->getCurrentHp() - self::DECREASE_POINTS);

        $this->userRepository->save($event->getUser(), true);

        //TODO: Use mercury auth
        $pushToken = $this->pushToken->create($user->getId());

        $update = new Update(
            'user/hp/' . $pushToken,
            json_encode(['currentHp' => $user->getCurrentHp()]),
        );
        $this->hub->publish($update);

        $update = new Update(
            'user/cash/' . $pushToken,
            json_encode(['cash' => $user->getCash()]),
        );
        $this->hub->publish($update);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MineClick::class => 'onMineClick',
        ];
    }
}
