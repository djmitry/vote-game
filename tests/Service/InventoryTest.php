<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Entity\UserShopItem;
use App\Repository\UserShopItemRepository;
use App\Service\Inventory;
use App\Service\Modifier\Modifier;
use PHPUnit\Framework\TestCase;

class InventoryTest extends TestCase
{
    public function testApply(): void
    {
        $shopItem = new UserShopItem();
        $shopItem->setShopItem((new ShopItem()));

        $repository = $this->createStub(UserShopItemRepository::class);
        $repository->method('findOneBy')->willReturn((new UserShopItem())->setShopItem(new ShopItem()));

        $modifier = $this->createStub(Modifier::class);
        $modifier->method('modify')->willReturn(true);

        $service = new Inventory($repository, $modifier);

        $actual = $service->applyItem(new User(), new ShopItem());

        $this->assertTrue($actual);
    }
}
