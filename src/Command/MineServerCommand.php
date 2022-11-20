<?php

declare(strict_types=1);

namespace App\Command;

use App\Event\MineMessage;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:mine-server',
    description: 'Add a short description for your command',
)]
class MineServerCommand extends Command
{
    public function __construct(private readonly MineMessage $message)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $server = IoServer::factory(new HttpServer(
            new WsServer(
                $this->message
            )
        ), 6001);

        $server->run();

        $io->success('Server started.');

        return Command::SUCCESS;
    }
}
