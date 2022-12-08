<?php

declare(strict_types=1);

namespace App\Service\Modifier;

use App\Entity\ShopItem;
use App\Entity\User;

interface ModifierInterface
{
    public function supports(int $type): bool;
    public function modify(User $user, ShopItem $shopItem): bool;
}