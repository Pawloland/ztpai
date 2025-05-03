<?php

namespace App\Repository;

use App\Entity\WorkerSessions;
use App\Enum\SessionVariant;
use App\Repository\BaseSessions\BaseSessionsRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkerSessions>
 */
class WorkerSessionsRepository extends BaseSessionsRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerSessions::class);
    }

    //    /**
    //     * @return workerSessions[] Returns an array of workerSessions objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?workerSessions
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
