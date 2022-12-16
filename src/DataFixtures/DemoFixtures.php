<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Entity\UserShopItem;
use App\Entity\Vote;
use App\Entity\VoteTransaction;
use App\Enum\BetCondition;
use App\Enum\VoteStatus;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//TODO: move to other fixtures
class DemoFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setCash(1000000000);
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                '111111'
            )
        );
        $user->setMaxHp(500);
        $user->setCurrentHp(500);

        $manager->persist($user);

        $users = [
            $user,
        ];

        for ($i = 0; $i < 15; $i++) {
            $user = new User();
            $user->setUsername('user_' . $i);
            $user->setCash((int)ceil(rand(9999, 99999) / 1000) * 1000);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    '111111'
                )
            );
            $user->setMaxHp(100);
            $user->setCurrentHp(10);

            $manager->persist($user);

            $users[] = $user;
        }

        $votes = [];
        $datetime = new DateTimeImmutable();
        $datetime = $datetime->modify('- 1 day');

        for ($i = 0; $i < 15; $i++) {
            $vote = new Vote();
            $vote->setTitle(uniqid());
            $vote->setUser($users[array_rand($users)]);
            $vote->setBet(rand(0, 9999999));
            $vote->setBetCondition(BetCondition::from(rand(0, 2)));
            $vote->setFinishedAt($datetime->modify('+ ' . (rand(1, 7) * 5) . ' minutes'));
            $manager->persist($vote);

            $votes[] = $vote;
        }

        for ($i = 0; $i < 15; $i++) {
            $bet = new VoteTransaction();
            $bet->setBet((int)ceil(rand(1000, 9000) / 100) * 100);
            $bet->setUser($users[array_rand($users)]);
            $bet->setVote($votes[array_rand($votes)]);
            $bet->setStatus(VoteStatus::BET);
            $bet->setBetCondition(BetCondition::from(rand(0, 2)));
            $bet->setCreatedAt((new DateTimeImmutable)->modify('- ' . rand(0, 1000) . ' min'));
            $manager->persist($bet);
        }

        $shopItems = [];


        $shopItemData = [
            [
                'title' => 'HP +100',
                'type' => 1,
                'value' => 100,
            ],
            [
                'title' => 'Mining +50%',
                'type' => 2,
                'value' => 50,
            ],
            [
                'title' => 'Win x2',
                'type' => 3,
                'value' => 2,
            ],
        ];

        for ($i = 0; $i < 15; $i++) {
            $itemData = $shopItemData[array_rand($shopItemData)];
            $item = new ShopItem();
            $item->setTitle($itemData['title']);
            $item->setSlug(uniqid());
            $item->setPrice((int)ceil(rand(500, 1500) / 100) * 100);
            $item->setType($itemData['type']);
            $item->setValue($itemData['value']);
            $shopItems[] = $item;

            $manager->persist($item);
        }

        for ($i = 0; $i < 15; $i++) {
            $userId = $users[$i];
            $shopItemId = $shopItems[$i];

            $item = new UserShopItem();
            $item->setUser($userId);
            $item->setShopItem($shopItemId);

            $manager->persist($item);
        }

        $manager->flush();
    }
}
