<?php

namespace App\Repository;

use App\Entity\ClientSessions;
use App\Repository\BaseSessions\BaseSessionsRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientSessions>
 */
class ClientSessionsRepository extends BaseSessionsRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientSessions::class);
    }

    //    /**
    //     * @return clientSessions[] Returns an array of clientSessions objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?clientSessions
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
