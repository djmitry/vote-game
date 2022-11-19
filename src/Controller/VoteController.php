<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use App\Form\BetType;
use App\Service\VoteService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    #[Route('/', name: 'vote_list')]
    public function list(ManagerRegistry $registry): Response
    {
        $votes = $registry->getRepository(Vote::class)->findAll();

        return $this->render('vote/index.html.twig', [
            'votes' => $votes,
        ]);
    }

    #[Route('/view/{vote}', name: 'vote_view')]
    public function view(Vote $vote, Request $request, VoteService $voteService): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $form = $this->createForm(BetType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
                $user = $this->getUser();

                $cash = $form->getData()['cash'];
                $condition = BetCondition::from($form->getData()['condition']);

                if ($voteService->createBet($vote, $user, $cash, $condition)) {
                    $this->addFlash('success', 'Bet created.');
                } else {
                    $this->addFlash('error', 'Bet error.');
                }

                return $this->redirectToRoute('vote_view', ['vote' => $vote->getId()]);
            }

            $formView = $form->createView();
        } else {
            $formView = null;
        }

        return $this->render('vote/view.html.twig', [
            'vote' => $vote,
            'form' => $formView,
        ]);
    }
}
