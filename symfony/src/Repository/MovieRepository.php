<?php

namespace App\Repository;

use App\Entity\movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, movie::class);
    }

    public function findAllMovies(): array
    {
        return $this->createQueryBuilder('m')
            ->getQuery()
            ->getResult();
    }

    public function findMovieById(int $id): ?movie
    {
        return $this->find($id);
    }

    public function addMovie(Movie $movie): Movie
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($movie);
        $entityManager->flush();

        return $movie;
    }

    public function deleteMovie(Movie $movie): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($movie);
        $entityManager->flush();
    }

    //    /**
    //     * @return movie[] Returns an array of movie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?movie
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
