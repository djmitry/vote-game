<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\ShopItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_inventory_')]
class InventoryController extends AbstractController
{
    #[Route('/inventory', name: 'list')]
    public function index(ShopItemRepository $shopItemRepository, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $items = $shopItemRepository->findUserItems($user->getId());

        if ($request->getMethod() === 'POST') {
            $id = (int) $request->get('id');

            $this->addFlash('danger', 'Can\'t apply.');

            return $this->redirectToRoute('app_inventory_list');
        }

        return $this->render('inventory/index.html.twig', [
            'shopItems' => $items,
        ]);
    }
}
