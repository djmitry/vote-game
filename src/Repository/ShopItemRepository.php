<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ShopItem;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShopItem>
 *
 * @method ShopItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopItem[]    findAll()
 * @method ShopItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopItem::class);
    }

    public function save(ShopItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShopItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ShopItem[] Returns an array of ShopItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShopItem
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    //TODO: exclude user product
    public function findNewItems()
    {
        return $this->createQueryBuilder('s')
            //->leftJoin('s.userShopItems', 'usi')
            //->leftJoin('usi.user', 'usiu')
            //->andWhere('IDENTITY(usi.user) != :user')
            //->setParameter('user', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ShopItem[]
     */
    public function findUserItems(int $userId): array
    {
        $builder =  $this->createQueryBuilder('s')
            ->leftJoin('s.userShopItems', 'usi')
            ->leftJoin('usi.user', 'usiu')
            ->andWhere('IDENTITY(usi.user) = :user')
            ->setParameter('user', $userId)
            ;

        //if ($type) {
        //    $builder
        //        ->andWhere('type = :type')
        //        ->setParameters(['type' => $type]);
        //}

        return $builder->getQuery()->getResult();
    }

    public function findUserActiveItems(User $user, int $type = null): array
    {
        $builder =  $this->createQueryBuilder('s')
            ->leftJoin('s.userShopItems', 'usi')
            ->leftJoin('usi.user', 'usiu')
            ->andWhere('usi.user = :user')
            ->andWhere('s.type = :type')
            ->andWhere('usi.status = 1')
            ->setParameters([
                'user' => $user,
                'type' => $type
            ]);

        return $builder->getQuery()->getResult();
    }
}
