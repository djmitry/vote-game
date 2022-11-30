<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\ShopItemRepository;
use App\Repository\UserShopItemRepository;
use InvalidArgumentException;

class ShopService
{
    public function __construct(
        private readonly UserShopItemRepository $userShopItemRepository,
        private readonly ShopItemRepository $shopItemRepository,
    )
    {
    }

    public function buy(int $shopItemId, User $user): bool
    {
        $shopItem = $this->shopItemRepository->find($shopItemId);

        if (!$shopItem) {
            throw new InvalidArgumentException('Shop item not found');
        }

        if ($this->userShopItemRepository->findOneBy(['user' => $user, 'shopItem' => $shopItem])) {
            return false;
        }

        return $this->userShopItemRepository->create($shopItem, $user);
    }
}