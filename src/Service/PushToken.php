<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class PushToken
{
    public function __construct(
        #[Autowire('%push_token_salt%')]
        private readonly string $pushSalt,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function create(int $userId): string
    {
        return hash('sha256', $userId . $this->pushSalt);
    }
}