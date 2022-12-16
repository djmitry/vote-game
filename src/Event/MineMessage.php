<?php

declare(strict_types=1);

namespace App\Event;

use App\Service\Mine\Mine;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MineMessage implements MessageComponentInterface
{
    protected array $connections = [];

    public function __construct(
        private readonly Mine $mine,
        private readonly JWTTokenManagerInterface $jwtManager,
    )
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
        $conn->send(json_encode(['error' => $e->getMessage()]));
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);
        $payload = $this->jwtManager->parse($data['token']);
        if (!isset($payload['id'])) {
            $from->close();
        }

        $userId = $payload['id'];
        $score = $this->mine->click(1, $payload['id']);

        $from->send(json_encode([
            'token' => $data['token'],
            'userId' => $userId,
            'score' => $score,
        ]));
    }
}