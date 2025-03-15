<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/api/addMovie', name: 'add_movie', methods: ['POST'])]
    public function addMovie(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'ID' => 1,
            'path' => 'src/Controller/AdminController.php',
        ]);
    }

    #[Route('/api/addScreening', name: 'add_screening', methods: ['POST'])]
    public function addScreening(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'ID' => 1,
            'path' => 'src/Controller/AdminController.php',
        ]);
    }

    #[Route('/api/removeUser/{ID_User}', name: 'remove_user', methods: ['DELETE'])]
    public function removeUser(int $ID_User): JsonResponse
    {
        return $this->json([
            'ID_User' => $ID_User,
            'path' => 'src/Controller/AdminController.php',
        ]);
    }

    #[Route('/api/removeClient/{ID_Client}', name: 'remove_client', methods: ['DELETE'])]
    public function removeClient(int $ID_Client): JsonResponse
    {
        return $this->json([
            '$ID_Client' => $ID_Client,
            'path' => 'src/Controller/AdminController.php',
        ]);
    }

    #[Route('/api/removeScreening/{ID_Screening}', name: 'remove_screening', methods: ['DELETE'])]
    public function removeScreening(int $ID_Screening): JsonResponse
    {
        return $this->json([
            'ID_Screening' => $ID_Screening,
            'path' => 'src/Controller/AdminController.php',
        ]);
    }

    #[Route('/api/removeReservation/{ID_Reservation}', name: 'remove_reservation', methods: ['DELETE'])]
    public function removeReservation(int $ID_Reservation): JsonResponse
    {
        return $this->json([
            'ID_Reservation' => $ID_Reservation,
            'path' => 'src/Controller/AdminController.php',
        ]);
    }

    #[Route('/api/removeMovie/{ID_Movie}', name: 'remove_movie', methods: ['DELETE'])]
    public function removeMovie(int $ID_Movie): JsonResponse
    {
        return $this->json([
            'ID_Movie' => $ID_Movie,
            'path' => 'src/Controller/AdminController.php',
        ]);
    }


}
