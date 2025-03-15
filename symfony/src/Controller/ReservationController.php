<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    #[Route('/api/selectPlace', name: 'select_place', methods: ['GET'])]
    public function selectPlace(Request $request): JsonResponse
    {
        $ID_Movie = $request->query->get('ID_Movie');
        if (!$ID_Movie) {
            return new JsonResponse(['error' => 'ID_Movie parameter is missing'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'ID_Movie' => $ID_Movie,
            'path' => 'src/Controller/ReservationController.php',
        ], Response::HTTP_OK);
    }

    #[Route('/api/getDiscount/{discount_name}', name: 'get_discount', methods: ['GET'])]
    public function getDiscount(int $discount_name): JsonResponse
    {
        return $this->json([
            'discount_name' => $discount_name,
            'path' => 'src/Controller/ReservationController.php',
        ]);
    }

    #[Route('/api/addReservation', name: 'add_reservation', methods: ['POST'])]
    public function addReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'ID' => 1,
            'path' => 'src/Controller/ReservationController.php',
        ]);
    }
}
