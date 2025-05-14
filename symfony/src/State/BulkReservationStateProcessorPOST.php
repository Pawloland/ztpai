<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\BulkReservationInput;
use App\Entity\BulkReservation;
use App\Entity\Client;
use App\Entity\Discount;
use App\Entity\Reservation;
use App\Entity\Screening;
use App\Entity\Seat;
use App\Enum\Globals;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BulkReservationStateProcessorPOST implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface     $persistProcessor,
        private RequestStack           $requestStack,
        private ValidatorInterface     $validator,
        private EntityManagerInterface $em
    )
    {
    }

    /**
     * @param BulkReservationInput $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): BulkReservation
    {
        if (!($data instanceof BulkReservationInput)) {
            throw new InvalidArgumentException('Expected ReservationInput');
        }

        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            throw new BadRequestHttpException("Request is missing");
        }

        if ($request->getMethod() !== Request::METHOD_POST) {
            throw new MethodNotAllowedHttpException(['POST'], 'Only POST method is allowed');
        }

        $client = $request->attributes->get(Globals::AUTHORIZED_ENTITY);
        if (!($client instanceof Client)) {
            throw new BadRequestHttpException("Authenticated client not found");
        }

        $screening = $this->em->getRepository(Screening::class)->find($data->id_screening);
        if (!$screening) {
            throw new BadRequestHttpException("Screening not found");
        }

        $seats = $this->em->getRepository(Seat::class)->findBy(['id_seat' => $data->id_seat, 'hall' => $screening->getHall()]);
        if (count($seats) < 1) {
            throw new BadRequestHttpException("No valid seats given");
        }

        $discount = $data->discount_name;
        if ($discount !== null) {
            $discount = $this->em->getRepository(Discount::class)->findOneBy(['discount_name' => (string)$data->discount_name]);
        }


        $bulkReservation = new BulkReservation();
        $this->em->persist($bulkReservation);
        $this->em->flush();
        $this->em->refresh($bulkReservation); // Refresh to get the ID


        $newReservation = new Reservation();
        $newReservation->setClient($client);
        $newReservation->setScreening($screening);
        $newReservation->setBulkReservation($bulkReservation);


        /** @var ReservationRepository $reservationRepository */
        $reservationRepository = $this->em->getRepository(Reservation::class);


        $reservationsOK = [];
        $reservationsBAD = [];


        foreach ($seats as $index => $seat) {
            $seatReservation = clone $newReservation;
            if ($index == 0) {
                $seatReservation->setDiscount($discount);
            } else {
                $seatReservation->setDiscount(null);
            }
            $seatReservation->setSeat($seat);
            try {
                $reservationRepository->addReservation($seatReservation);
                $reservationsOK[] = $seatReservation;
            } catch (Exception $e) {
                $reservationsBAD[] = [$seatReservation, $e];
            }

        }
        $this->em->refresh($bulkReservation); // Refresh to get all added reservations
        $bulkReservation->setClosed(true);
        $this->em->persist($bulkReservation);
        $this->em->flush();
        $this->em->refresh($bulkReservation); // Refresh to get the closed bulk reservation
        if (count(iterator_to_array($bulkReservation->getReservation())) < 1) {
            $this->em->remove($bulkReservation);
            $this->em->flush();
            throw new BadRequestHttpException("No valid seats passed or all seats are already reserved");
        }
        return $bulkReservation;
    }

}
