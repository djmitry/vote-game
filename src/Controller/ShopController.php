<?php

declare(strict_types=1);

namespace App\Controller;

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
        //TODO: collection form or ajax
        //$form = $this->createForm(ShopItemType::class);
        //$form->handleRequest($request);
        //
        //$data = [];

        //foreach ($shopItemRepository->findAll() as $item) {
        //    $data[] = [
        //        'id' => $item->getId(),
        //        'type' => $item->getId(),
        //        'price' => $item->getId(),
        //    ];
        //}

        //if ($form->isSubmitted() && $form->isValid()) {
        //
        //}

        if ($request->getMethod() === 'POST') {
            $id = (int) $request->get('id');

            if ($shopService->buy($id, $this->getUser())) {
                $this->addFlash('success', 'Bought.');
            } else {
                $this->addFlash('danger', 'Can\'t buy.');
            }

            return $this->redirectToRoute('app_shop_list');
        }

        return $this->render('shop/index.html.twig', [
            //'form' => $form->createView(),
            'shopItems' => $shopItemRepository->findNewItems(),
        ]);
    }
}
