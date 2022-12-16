<?php

declare(strict_types=1);

namespace App\Tests\Service\Modifier;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Enum\ShopItemType;
use App\Repository\UserRepository;
use App\Service\Modifier\HpModifier;
use PHPUnit\Framework\TestCase;

class HpModifierTest extends TestCase
{
    public function testSupports()
    {
        $repository = $this->createStub(UserRepository::class);
        $service = new HpModifier($repository);

        $actual = $service->supports(ShopItemType::HP);

        $this->assertTrue($actual);
    }

    public function testModify()
    {
        $repository = $this->createStub(UserRepository::class);

        $service = new HpModifier($repository);
        $shopItem = new ShopItem();

        $actual = $service->modify(new User(), $shopItem);

        $this->assertTrue($actual);
    }
}
