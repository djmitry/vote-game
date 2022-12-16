<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ShopItem;
use App\Entity\User;
use App\Entity\UserShopItem;
use App\Enum\UserShopItemStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserShopItem>
 *
 * @method UserShopItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserShopItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserShopItem[]    findAll()
 * @method UserShopItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserShopItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserShopItem::class);
    }

    public function save(UserShopItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserShopItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserShopItem[] Returns an array of UserShopItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserShopItem
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function create(ShopItem $shopItem, User $user): bool
    {
        $item = new UserShopItem();
        $item->setUser($user);
        $item->setShopItem($shopItem);

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return true;
    }

    public function changeStatus(UserShopItem $userShopItem, UserShopItemStatus $status): void
    {
        $userShopItem->setStatus($status);

        $this->getEntityManager()->flush();
    }
}
