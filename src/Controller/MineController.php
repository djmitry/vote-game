<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MineController extends AbstractController
{
    #[Route('/mine', name: 'app_mine')]
    public function index(): Response
    {
        return $this->render('mine/index.html.twig', [
        ]);
    }
}
