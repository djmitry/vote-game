<?php

namespace App\Service;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Repository\UserShopItemRepository;
use App\Service\Modifier\Modifier;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Inventory
{
    public function __construct(
        private readonly UserShopItemRepository $userShopItemRepository,
        //EventDispatcherInterface $eventDispatcher,
        private readonly Modifier $modifier,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function applyItem(User $user, ShopItem $shopItem): bool
    {
        $userShopItem = $this->userShopItemRepository->findOneBy(['user' => $user, 'shopItem' => $shopItem]);

        if (!$userShopItem) {
            throw new Exception('Item no found.');
        }

        $this->userShopItemRepository->changeStatus($userShopItem, 1);

        return $this->modifier->modify($user, $userShopItem->getShopItem());
    }
}