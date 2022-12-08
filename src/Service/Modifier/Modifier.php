<?php

declare(strict_types=1);

namespace App\Service\Modifier;

use App\Entity\ShopItem;
use App\Entity\User;

class Modifier
{
    /**
     * @param ModifierInterface[] $modifiers
     */
    public function __construct(private readonly array $modifiers)
    {
    }

    public function modify(User $user, ShopItem $shopItem): bool
    {
        if (!$this->modifiers) {
            return false;
        }

        foreach ($this->modifiers as $modifier) {
            if ($modifier->supports($shopItem->getType())) {
                $modifier->modify($user, $shopItem);
            }
        }

        return true;
    }
}