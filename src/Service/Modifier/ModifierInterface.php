<?php

declare(strict_types=1);

namespace App\Service\Modifier;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Enum\ShopItemType;

interface ModifierInterface
{
    public function supports(ShopItemType $type): bool;
    public function modify(User $user, ShopItem $shopItem): bool;
}