<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use App\Form\BetType;
use App\Repository\VoteRepository;
use App\Service\FileUploader;
use App\Service\VoteService;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[Route(name: 'app_vote_')]
class VoteController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(ManagerRegistry $registry): Response
    {
        $votes = $registry->getRepository(Vote::class)->findAll();

        return $this->render('vote/index.html.twig', [
            'votes' => $votes,
        ]);
    }

    //TODO: factory
    #[Route('/vote/create')]
    public function createVote(Request $request, VoteRepository $voteRepository, FileUploader $fileUploader): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            throw new AuthenticationException();
        }

        /** @var User $user */
        $user = $this->getUser();

        try {
            $vote = new Vote();
            $vote->setTitle($request->request->get('title'));
            $vote->setDescription($request->request->get('description'));
            $vote->setBet((int)$request->request->get('bet'));
            $vote->setBetCondition(BetCondition::from((int)$request->request->get('betCondition')));
            $vote->setUser($user);
            $date = new DateTimeImmutable();
            $date = $date->modify('+ 5 minutes');
            $vote->setFinishedAt($date);
            $fileName = $fileUploader->upload($request->files->get('file'), 'upload/vote');
            $vote->setImage($fileName);
            $voteRepository->save($vote, true);

            $success = true;
            $message = 'Vote created.';
        } catch (FileException $e) {
            $success = false;
            $message = 'File error.';
        } catch (\Throwable $e) {
            $success = false;
            $message = 'Vote error.';
        }

        return new JsonResponse(['success' => $success, 'message' => $message]);
    }

    #[Route('/vote/view/{vote}', name: 'view')]
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

                return $this->redirectToRoute('app_vote_view', ['vote' => $vote->getId()]);
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
