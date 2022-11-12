<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vote;
use App\Enum\BetCondition;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//    TODO: Add more fixtures
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
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

        $userIds = [];

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername('user_' . uniqid());
            $user->setCash(rand(100, 9999));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    '111111'
                )
            );

            $manager->persist($user);

            $userIds[] = $user;
        }

        $datetime = new DateTimeImmutable();

        for ($i = 0; $i < 10; $i++) {
            $vote = new Vote();
            $vote->setTitle(uniqid());
            $vote->setUser($userIds[rand(0, count($userIds) - 1)]);
            $vote->setBet((string) rand(0, 9999999));
            $vote->setBetCondition(BetCondition::from(rand(0, 2)));
            $vote->setFinishedAt($datetime->modify('+ ' . (rand(1, 7) * 5) . ' minutes'));
            $manager->persist($vote);
        }

        $manager->flush();
    }
}
