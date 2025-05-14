<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function addReservation(Reservation &$reservation): void
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT * from new_reservation(
                :id_seat,
                :id_screening,
                :id_discount,
                :id_client,
                :id_bulk_reservation,
                :vat_percentage,
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            )
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue('id_seat', $reservation->getSeat()->getIdSeat());
        $stmt->bindValue('id_screening', $reservation->getScreening()->getIdScreening());
        $stmt->bindValue('id_discount', $reservation->getDiscount()?->getIdDiscount());
        $stmt->bindValue('id_client', $reservation->getClient()->getIdClient());
        $stmt->bindValue('id_bulk_reservation', $reservation->getBulkReservation()->getIdBulkReservation());
        $stmt->bindValue('vat_percentage', 23);

        $result = $stmt->executeQuery()->fetchAssociative();

        //cast result to reservation object
        $reservation->setIdReservation($result['id_reservation']);
        $reservation->setTotalPriceNetto($result['total_price_netto']);
        $reservation->setTotalPriceBrutto($result['total_price_brutto']);
        $reservation->setVatPercentage($result['vat_percentage']);
        $reservation->setReservationDate(new DateTimeImmutable($result['reservation_date']));
        $reservation->setNip('');
        $reservation->setNrb('');
        $reservation->setAddressStreet('');
        $reservation->setAddressNr('');
        $reservation->setAddressFlat('');
        $reservation->setAddressCity('');
        $reservation->setAddressZip('');

    }

    //    /**
    //     * @return reservation[] Returns an array of reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
