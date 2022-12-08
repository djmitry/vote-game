<?php

declare(strict_types=1);

namespace App\Tests\Service\Modifier;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Modifier\HpModifier;
use App\Service\Modifier\Modifier;
use PHPUnit\Framework\TestCase;

class ModifierTest extends TestCase
{
    public function testModify(): void
    {
        $service = new Modifier([
            new HpModifier($this->createMock(UserRepository::class)),
        ]);

        $actual = $service->modify(new User(), (new ShopItem())->setType(1));

        $this->assertTrue($actual);
    }
}
