<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Mine;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class MineTest extends TestCase
{
    /**
     * @dataProvider clickDataProvider
     */
    public function testClick(int $basePoints, int $rate, int $time): void
    {
        $session = $this->createStub(Session::class);
        $session->method('get')->willReturn($time);
        $request = new Request();
        $request->setSession($session);
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $service = new Mine($requestStack);

        $actual = $service->click($basePoints);

        $this->assertEquals($basePoints * $rate, $actual);
    }

    public function clickDataProvider(): array
    {
        return [
            [
                2,
                1,
                time() * 1000 - 1000,
            ],
            [
                3,
                2,
                time() * 1000 - 400,
            ],
            [
                10,
                3,
                time() * 1000 - 300,
            ],
            [
                12,
                4,
                time() * 1000 - 200,
            ],
            [
                25,
                5,
                time() * 1000 - 100,
            ],
        ];
    }

}
