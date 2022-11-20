<?php

declare(strict_types=1);

namespace App\Event;

use App\Service\Mine;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MineMessage implements MessageComponentInterface
{
    protected array $connections = [];

    public function __construct(private readonly Mine $mine)
    {
    }

    function onOpen(ConnectionInterface $conn)
    {
        $conn->send('Open connection.');
        $this->connections[] = $conn;
    }

    function onClose(ConnectionInterface $conn)
    {
        $conn->send('Close.');
        array_filter($this->connections, fn($connection) => $connection !== $conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send('Error. ' . $e->getMessage());
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $score = $this->mine->click(2, 1);
        $from->send('Thanks for the message: ' . $msg . ', your score is: ' . $score);
    }
}