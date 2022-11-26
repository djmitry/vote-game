<?php

declare(strict_types=1);

namespace App\Controller;

use Redis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MineController extends AbstractController
{
    #[Route('/mine', name: 'app_mine')]
    public function index(Redis $redis): Response
    {
        $token = 'token_' . sha1((string)$this->getUser()->getId());

        if (!$redis->get($token)) {
            $redis->set($token, $this->getUser()->getId());
        }

        return $this->render('mine/index.html.twig', [
            'token' => $token,
        ]);
    }
}
