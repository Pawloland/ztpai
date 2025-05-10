<?php

namespace App\Repository;

use App\Entity\Hall;
use App\Entity\Movie;
use App\Entity\Screening;
use App\Entity\ScreeningType;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Screening>
 */
class ScreeningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Screening::class);
    }

    public function addScreening(Screening &$screening): void
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT * FROM new_screening(
                :id_movie,
                :id_hall,
                :id_screening_type,
                :start_time
            )
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue('id_movie', $screening->getMovie()->getIdMovie());
        $stmt->bindValue('id_hall',  $screening->getHall()->getIdHall());
        $stmt->bindValue('id_screening_type',  $screening->getScreeningType()->getIdScreeningType());
        $stmt->bindValue('start_time', $screening->getStartTime()->format('Y-m-d H:i:s'));

        $result = $stmt->executeQuery()->fetchAssociative();

        //cast result to screening object
        $screening->setIdScreening($result['id_screening']);
        $screening->setStartTime(new DateTimeImmutable($result['start_time']));
        $screening->setMovie($this->getEntityManager()->getRepository(Movie::class)->find($result['id_movie']));
        $screening->setHall($this->getEntityManager()->getRepository(Hall::class)->find($result['id_hall']));
        $screening->setScreeningType($this->getEntityManager()->getRepository(ScreeningType::class)->find($result['id_screening_type']));

    }

    //    /**
    //     * @return screening[] Returns an array of screening objects
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

    //    public function findOneBySomeField($value): ?screening
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
