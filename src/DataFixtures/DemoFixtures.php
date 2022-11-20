<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vote;
use App\Entity\VoteTransaction;
use App\Enum\BetCondition;
use App\Enum\BetStatus;
use App\Service\VoteService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemoFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setCash(PHP_INT_MAX);
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

        for ($i = 0; $i < 10; $i++) {
            $bet = new VoteTransaction();
            $bet->setBet(rand(1000, 9999));
            $bet->setUser($users[rand(0, count($users) - 1)]);
            $bet->setVote($votes[rand(0, count($votes) - 1)]);
            $bet->setStatus(BetStatus::from(rand(0, 2)));
            $bet->setBetCondition(BetCondition::from(rand(0, 2)));
            $bet->setCreatedAt((new DateTimeImmutable)->modify('- ' . rand(0, 1000) . ' min'));
            $manager->persist($bet);
        }

        $manager->flush();
    }
}
