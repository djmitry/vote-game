<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Repository\ShopItemRepository;
use App\Service\Inventory;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
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
        $shopItems = [];

        foreach ($items as $item) {
            $criteria = new Criteria();
            $expr = new Comparison('shopItem', '=', $item);
            $criteria->where($expr);
            $userShopItem = $user->getUserShopItems()->matching($criteria)->first();

            $shopItems[] = [
                'id' => $item->getId(),
                'type' => $item->getId(),
                'title' => $item->getTitle(),
                'status' => $userShopItem->getStatus(),
            ];
        }

        return $this->render('inventory/index.html.twig', [
            'shopItems' => $shopItems,
        ]);
    }

    #[Route('/inventory/{shopItem}/apply', name: 'apply', methods: ['POST'])]
    public function apply(Request $request, Inventory $inventory, ShopItem $shopItem): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($inventory->applyItem($user, $shopItem)) {
            $this->addFlash('success', 'Item applied.');
        } else {
            $this->addFlash('danger', 'Can\'t apply.');
        }

        return $this->redirectToRoute('app_inventory_list');
    }
}
