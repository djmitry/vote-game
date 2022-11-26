<?php

declare(strict_types=1);

namespace App\Event;

use App\Repository\UserRepository;
use App\Service\Mine;
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
        $score = $this->mine->click(2, 1);
        $userId = $this->redis->get($data['token']);
        $user = $this->userRepository->find($userId);
        $this->userRepository->addCash($user, $score);

        $from->send(json_encode(['message' => 'Thanks for the message: ' . $data['token']  . $userId.  ', your score is: ' . $score, 'score' => $score]));
    }
}