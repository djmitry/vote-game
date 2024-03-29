<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\BetDto;
use App\Entity\Vote;
use App\Entity\VoteTransaction;
use App\Enum\VoteStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VoteTransaction>
 *
 * @method VoteTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteTransaction[]    findAll()
 * @method VoteTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteTransaction::class);
    }

    public function save(VoteTransaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VoteTransaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return VoteTransaction[] Returns an array of VoteTransaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VoteTransaction
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function create(BetDto $betDto): VoteTransaction
    {
        $user = $betDto->getUser();
        $user->setCash($betDto->getUserCash());

        $transaction = new VoteTransaction();
        $transaction->setVote($betDto->getVote());
        $transaction->setUser($betDto->getUser());
        $transaction->setBet($betDto->getBet());
        $transaction->setBetCondition($betDto->getCondition());
        $transaction->setStatus($betDto->getStatus());

        $this->getEntityManager()->persist($transaction);
        $this->getEntityManager()->flush();

        return $transaction;
    }

    /**
     * @return VoteTransaction[]
     */
    public function findWinners(Vote $vote): array
    {
        return $this->createQueryBuilder('vt')
            ->andWhere('vt.vote = :vote')
            ->andWhere('vt.betCondition = :voteCondition')
            ->andWhere('vt.status = :status')
            ->setParameters([
                'vote' => $vote,
                'voteCondition' => $vote->getBetCondition(),
                'status' => VoteStatus::BET,
            ])
            ->orderBy('vt.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return VoteTransaction[]
     */
    public function findLosers(Vote $vote): array
    {
        return $this->createQueryBuilder('vt')
            ->andWhere('vt.vote = :vote')
            ->andWhere('vt.betCondition != :voteCondition')
            ->andWhere('vt.status = :status')
            ->setParameters([
                'vote' => $vote,
                'voteCondition' => $vote->getBetCondition(),
                'status' => VoteStatus::BET,
            ])
            ->orderBy('vt.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
