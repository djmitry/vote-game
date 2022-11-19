<?php

declare(strict_types=1);

namespace App\Controller;

use App\DtoFactory\VoteTransactionDtoFactory;
use App\Repository\VoteTransactionRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_bet_')]
class BetController extends AbstractController
{
    #[Route('/bet', name: 'list')]
    public function index(VoteTransactionRepository $transactionRepository, VoteTransactionDtoFactory $dtoFactory): Response
    {
        $bets = $transactionRepository->findBy([], ['createdAt' => Criteria::DESC]);
        $betsDto = $dtoFactory->create($bets);

        return $this->render('bet/index.html.twig', [
            'bets' => $betsDto,
        ]);
    }
}
