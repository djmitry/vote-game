<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use App\Service\VoteService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemoFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private VoteService $voteService;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, VoteService $voteService)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->voteService = $voteService;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                '111111'
            )
        );

        $manager->persist($user);

        $users = [];

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername('user_' . uniqid());
            $user->setCash(rand(9999, 99999));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    '111111'
                )
            );

            $manager->persist($user);

            $users[] = $user;
        }

        $votes = [];
        $datetime = new DateTimeImmutable();

        for ($i = 0; $i < 10; $i++) {
            $vote = new Vote();
            $vote->setTitle(uniqid());
            $vote->setUser($users[rand(0, count($users) - 1)]);
            $vote->setBet((string) rand(0, 9999999));
            $vote->setBetCondition(BetCondition::from(rand(0, 2)));
            $vote->setFinishedAt($datetime->modify('+ ' . (rand(1, 7) * 5) . ' minutes'));
            $manager->persist($vote);

            $votes[] = $vote;
        }

        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
//            $vote = new VoteTransaction();
//            $vote->setUser($users[rand(0, count($users) - 1)]);
//            $vote->setVote($votes[rand(0, count($votes) - 1)]);
//            $vote->setBet((string) rand(0, 9999999));
//            $vote->setBetCondition(BetCondition::from(rand(0, 2)));
//            $vote->setFinishedAt($datetime->modify('+ ' . (rand(1, 7) * 5) . ' minutes'));
//            $manager->persist($vote);


            $this->voteService->createBet($this->voteService->prepareBet(
                $votes[rand(0, count($votes) - 1)],
                $users[rand(0, count($users) - 1)],
                rand(100, 999))
            );

            $votes[] = $vote;
        }
    }
}
