<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Security;

class JwtToken
{
    public function __construct(
        private readonly Security $security,
        private readonly JWTTokenManagerInterface $jwtManager,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function create(): string
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            throw new BadRequestException();
        }

        /** @var User $user */
        $user = $this->security->getUser();

        return $this->jwtManager->createFromPayload($user, ['id' => $user->getId()]);
    }
}