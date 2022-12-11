<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\VoteRepository;
use App\Service\VoteService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:finish-votes',
    description: 'Finish votes',
)]
class FinishVotesCommand extends Command
{
    public function __construct(
        private readonly VoteRepository $voteRepository,
        private readonly VoteService $voteService,
        string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->voteRepository->findFinished() as $vote) {
            $this->voteService->handleBet($vote);
        }

        $io->success('Finished.');

        return Command::SUCCESS;
    }
}
