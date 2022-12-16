<?php

declare(strict_types=1);

namespace App\Service\Modifier;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Enum\ShopItemType;
use App\Repository\UserRepository;

class HpModifier implements ModifierInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function supports(ShopItemType $type): bool
    {
        return $type === ShopItemType::HP;
    }

    public function modify(User $user, ShopItem $shopItem): bool
    {
        $this->userRepository->updateMaxHp($user, $user->getMaxHp() + $shopItem->getValue());

        return true;
    }
}