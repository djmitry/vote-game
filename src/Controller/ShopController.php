<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\ShopItemRepository;
use App\Service\ShopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_shop_')]
class ShopController extends AbstractController
{
    #[Route('/shop', name: 'list')]
    public function index(ShopItemRepository $shopItemRepository, Request $request, ShopService $shopService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($request->getMethod() === 'POST') {
            $id = (int) $request->get('id');

            if ($shopService->buy($id, $user)) {
                $this->addFlash('success', 'Bought.');
            } else {
                $this->addFlash('danger', 'Can\'t buy.');
            }

            return $this->redirectToRoute('app_shop_list');
        }

        $items = $shopItemRepository->findNewItems($user);
        $shopItems = [];

        foreach ($items as $item) {
            $shopItems[] = [
                'id' => $item->getId(),
                'type' => $item->getType()->value,
                'title' => $item->getTitle(),
                'price' => $item->getPrice(),
            ];
        }

        return $this->render('shop/index.html.twig', [
            'shopItems' => $shopItems,
        ]);
    }
}
