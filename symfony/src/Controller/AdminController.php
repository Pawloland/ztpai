<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    // Users endpoints
    #[Route('/api/users', name: 'get_all_users', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        return $this->json([
            'message' => 'List of all users',
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/users/{id}', name: 'get_user', methods: ['GET'])]
    public function getUserDetails(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Single user details',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/users', name: 'add_user', methods: ['POST'])]
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        return $this->json([
            'message' => 'User created',
            'data' => $data,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'User deleted',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    // Movies endpoints
    #[Route('/api/movies', name: 'get_all_movies', methods: ['GET'])]
    public function getAllMovies(): JsonResponse
    {
        return $this->json([
            'message' => 'List of all movies',
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/movies/{id}', name: 'get_movie', methods: ['GET'])]
    public function getMovie(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Single movie details',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/movies', name: 'add_movie', methods: ['POST'])]
    public function addMovie(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        return $this->json([
            'message' => 'Movie created',
            'data' => $data,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/movies/{id}', name: 'delete_movie', methods: ['DELETE'])]
    public function deleteMovie(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Movie deleted',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    // Screenings endpoints
    #[Route('/api/screenings', name: 'get_all_screenings', methods: ['GET'])]
    public function getAllScreenings(): JsonResponse
    {
        return $this->json([
            'message' => 'List of all screenings',
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/screenings/{id}', name: 'get_screening', methods: ['GET'])]
    public function getScreening(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Single screening details',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/screenings', name: 'add_screening', methods: ['POST'])]
    public function addScreening(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        return $this->json([
            'message' => 'Screening created',
            'data' => $data,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/screenings/{id}', name: 'delete_screening', methods: ['DELETE'])]
    public function deleteScreening(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Screening deleted',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    // Reservations endpoints
    #[Route('/api/reservations', name: 'get_all_reservations', methods: ['GET'])]
    public function getAllReservations(): JsonResponse
    {
        return $this->json([
            'message' => 'List of all reservations',
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/reservations/{id}', name: 'get_reservation', methods: ['GET'])]
    public function getReservation(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Single reservation details',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/reservations', name: 'add_reservation', methods: ['POST'])]
    public function addReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        return $this->json([
            'message' => 'Reservation created',
            'data' => $data,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }

    #[Route('/api/reservations/{id}', name: 'delete_reservation', methods: ['DELETE'])]
    public function deleteReservation(int $id): JsonResponse
    {
        return $this->json([
            'message' => 'Reservation deleted',
            'id' => $id,
            'path' => 'src/Controller/AdminController.php'
        ]);
    }
}
