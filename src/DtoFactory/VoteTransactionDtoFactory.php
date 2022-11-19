<?php

declare(strict_types=1);

namespace App\DtoFactory;

use App\Dto\VoteTransactionDto;
use App\Entity\VoteTransaction;

class VoteTransactionDtoFactory
{
    /**
     * @param array<VoteTransaction> $transactions
     * @return array<VoteTransactionDto>
     */
    public function create(array $transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $result[] = $this->createOne($transaction);
        }

        return $result;
    }

    public function createOne(VoteTransaction $transaction): VoteTransactionDto
    {
        return new VoteTransactionDto(
            $transaction->getUser()->getUsername(),
            $transaction->getVote()->getTitle(),
            $transaction->getVote()->getId(),
            $transaction->getVote()->getFinishedAt(),
            $transaction->getBet(),
            $transaction->getWin(),
            $transaction->getBetCondition()->label(),
            $transaction->getStatus()->label(),
            $transaction->getCreatedAt(),
        );
    }
}