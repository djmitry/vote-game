<?php

declare(strict_types=1);

namespace App\Event;

use App\Repository\UserRepository;
use App\Service\Mine\Mine;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Redis;

class MineMessage implements MessageComponentInterface
{
    protected array $connections = [];

    public function __construct(private readonly Mine $mine, private readonly Redis $redis, private readonly UserRepository $userRepository)
    {
    }

    function onOpen(ConnectionInterface $conn)
    {
        $conn->send(json_encode(['Open connection.']));
        $this->connections[] = $conn;
    }

    function onClose(ConnectionInterface $conn)
    {
        $conn->send(json_encode(['Close.']));
        array_filter($this->connections, fn($connection) => $connection !== $conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send(json_encode(['Error. ' . $e->getMessage()]));
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);
        $userId = $this->redis->get($data['token']);
        $user = $this->userRepository->find($userId);

        $score = $this->mine->click(1, $user->getId());
        $this->userRepository->addCash($user, $score);

        $time = $this->redis->get('mineClickTime' . $userId);

        $from->send(json_encode([
            'message' => 'Thanks for the message: ' . $data['token']  . $userId.  ', your score is: ' . $score,
            'score' => $score,
            'time' => $time,
        ]));
    }
}