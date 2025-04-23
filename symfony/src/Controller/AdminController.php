<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\LanguageRepository;
use App\Repository\MovieRepository;
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
    public function getAllMovies(MovieRepository $movieRepository): JsonResponse
    {
        $movies = $movieRepository->findAllMovies();
        $movieData = array_map(function ($movie) {
            return [
                'id_movie' => $movie->getIdMovie(),
                'title' => $movie->getTitle(),
                'original_title' => $movie->getOriginalTitle(),
                'duration' => $movie->getDuration()->format('H:i:s'),
                'description' => $movie->getDescription(),
                'poster' => $movie->getPoster(),
                'id_language' => $movie->getIdLanguage(),
                'id_dubbing' => $movie->getIdDubbing(),
                'id_subtitles' => $movie->getIdSubtitles()
            ];
        }, $movies);

        return $this->json($movieData, Response::HTTP_OK);
    }

    #[Route('/api/movies', name: 'add_movie', methods: ['POST'])]
    public function addMovie(Request $request, MovieRepository $movieRepository, LanguageRepository $languageRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        // Validate required fields
        $requiredFields = ['title', 'original_title', 'duration', 'description', 'poster', 'id_language', 'id_dubbing', 'id_subtitles'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return new JsonResponse(['error' => "Missing field: $field"], Response::HTTP_BAD_REQUEST);
            }
        }

        // Validate duration format
        $duration = \DateTime::createFromFormat('H:i:s', $data['duration']);
        if (!$duration) {
            return new JsonResponse(['error' => 'Invalid duration format. Use H:i:s'], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Fetch language entities
            $language = $languageRepository->find($data['id_language']);
            $dubbing = $languageRepository->find($data['id_dubbing']);
            $subtitles = $languageRepository->find($data['id_subtitles']);

            if (!$language || !$dubbing || !$subtitles) {
                return new JsonResponse(['error' => 'Invalid language, dubbing, or subtitles ID'], Response::HTTP_BAD_REQUEST);
            }

            // Create a new Movie entity
            $movie = new Movie();
            $movie->setTitle($data['title']);
            $movie->setOriginalTitle($data['original_title']);
            $movie->setDuration($duration);
            $movie->setDescription($data['description']);
            // $movie->setPoster($data['poster']);
            $movie->setPoster(NULL); // should be a valid UUID, so temporarily set to NULL, just for basic functionality
            $movie->setLanguageViaIdLanguage($language);
            $movie->setLanguageViaIdDubbing($dubbing);
            $movie->setLanguageViaIdSubtitles($subtitles);

            // Save the movie using the repository
            $newMovie = $movieRepository->addMovie($movie);

            return $this->json([
                'message' => 'Movie created',
                'data' => [
                    'id_movie' => $newMovie->getIdMovie(),
                    'title' => $newMovie->getTitle(),
                    'original_title' => $newMovie->getOriginalTitle(),
                    'duration' => $newMovie->getDuration()->format('H:i:s'),
                    'description' => $newMovie->getDescription(),
                    'poster' => $newMovie->getPoster(),
                    'id_language' => $newMovie->getIdLanguage(),
                    'id_dubbing' => $newMovie->getIdDubbing(),
                    'id_subtitles' => $newMovie->getIdSubtitles(),
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/movies/{id}', name: 'get_movie', methods: ['GET'])]
    public function getMovie(int $id, MovieRepository $movieRepository): JsonResponse
    {
        $movie = $movieRepository->findMovieById($id);

        if (!$movie) {
            return $this->json(['error' => 'Movie not found'], Response::HTTP_NOT_FOUND);
        }

        $movieData = [
            'id_movie' => $movie->getIdMovie(),
            'title' => $movie->getTitle(),
            'original_title' => $movie->getOriginalTitle(),
            'duration' => $movie->getDuration()->format('H:i:s'),
            'description' => $movie->getDescription(),
            'poster' => $movie->getPoster(),
            'id_language' => $movie->getIdLanguage(),
            'id_dubbing' => $movie->getIdDubbing(),
            'id_subtitles' => $movie->getIdSubtitles()
        ];

        return $this->json($movieData, Response::HTTP_OK);
    }

    #[Route('/api/movies/{id}', name: 'delete_movie', methods: ['DELETE'])]
    public function deleteMovie(int $id, MovieRepository $movieRepository): JsonResponse
    {
        try {
            $movie = $movieRepository->findMovieById($id);

            if (!$movie) {
                return $this->json(['error' => 'Movie not found'], Response::HTTP_NOT_FOUND);
            }

            $movieRepository->deleteMovie($movie);

            return $this->json([
                'message' => 'Movie deleted successfully',
                'id' => $id
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
