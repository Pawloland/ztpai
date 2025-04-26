<?php

namespace App\Controller;

use App\Entity\Worker;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{

    private const DAYS = 0;
    private const HOURS = 0;
    private const MINUTES = 90;

    private const  COOKIE_NAME = 'auth';
    private const  COOKIE_NAME_ADMIN = 'auth_admin';

    #[Route('/api/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Niepoprawne dane'], Response::HTTP_BAD_REQUEST);
        }


        return $this->json([
            'login' => 'OK',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }

    #[Route('/api/adminLogin', name: 'admin_login', methods: ['POST'])]
    public function adminLogin(Request $request, EntityManagerInterface $em, Connection $connection): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $nick = $data['nick'] ?? null;
        $password = $data['password'] ?? null;

        if (!$nick || !$password) {
            return new JsonResponse(['error' => 'Missing credentials.'], Response::HTTP_BAD_REQUEST);
        }

        $worker = $em->getRepository(Worker::class)->findOneBy(['nick' => $nick]);
        if (!$worker || !password_verify($password, $worker->getPasswordHash())) {
            return new JsonResponse(['error' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        $stmt = $connection->executeQuery(
            "SELECT * FROM create_session(:id, 'w', :days, :hours, :minutes, 'UTC')",
            [
                'id' => $worker->getIdWorker(),
                'days' => self::DAYS,
                'hours' => self::HOURS,
                'minutes' => self::MINUTES
            ]
        );
        $result = $stmt->fetchAssociative();

        if (!$result || empty($result['session_token']) || empty($result['expiration_date'])) {
            return new JsonResponse(['error' => 'Failed to create session.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $token = $result['session_token'];
        $expirationDate = $result['expiration_date'];

        $cookieValue = json_encode(['token' => $token, 'nick' => $worker->getNick()]);

        $response = new JsonResponse(['message' => 'Login successful']);
        $response->headers->setCookie(
            new Cookie(
                self::COOKIE_NAME_ADMIN,
                $cookieValue,
                strtotime($expirationDate . ' UTC'),
                '/',
                null,
                true,
                true,
                false,
                'Strict'
            )
        );

        return $response;
    }


    #[Route('/api/logout', name: 'logout', methods: ['GET'])]
    public function logout(Request $request): JsonResponse
    {
        return $this->json([
            'logout' => 'OK',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }

    #[Route('/api/adminLogout', name: 'admin_logout', methods: ['GET'])]
    public function adminLogout(Request $request, Connection $connection, EntityManagerInterface $em): JsonResponse
    {
        $cookieValue = $request->cookies->get(self::COOKIE_NAME_ADMIN);

        if (!$cookieValue) {
            return new JsonResponse(['error' => 'No session found.'], Response::HTTP_BAD_REQUEST);
        }

        $cookieData = json_decode($cookieValue, true);
        $token = $cookieData['token'] ?? null;
        $nick = $cookieData['nick'] ?? null;

        if (!$token || !$nick) {
            return new JsonResponse(['error' => 'Invalid session token or nick.'], Response::HTTP_BAD_REQUEST);
        }

        // Retrieve worker by nick
        $worker = $em->getRepository(Worker::class)->findOneBy(['nick' => $nick]);
        if (!$worker) {
            return new JsonResponse(['error' => 'Worker not found.'], Response::HTTP_BAD_REQUEST);
        }

        $workerId = $worker->getIdWorker();

        // Call stored procedure to delete session
        $stmt = $connection->executeQuery(
            "SELECT * FROM delete_session(:id, 'w', :token)",
            [
                'id' => $workerId,
                'token' => $token
            ]
        );

        $result = $stmt->fetchAssociative();

        if (!$result || !$result['delete_session']) {
            return new JsonResponse(['error' => 'Failed to delete session.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Prepare response and delete the cookie
        $response = new JsonResponse(['message' => 'Logout successful']);
        $response->headers->clearCookie(
            self::COOKIE_NAME_ADMIN,
            '/',
            null,
            true,
            true,
            'Strict'
        );

        return $response;
    }


    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE ||
            !isset($data['email']) || !isset($data['password']) || !isset($data['password_rep'])) {
            return new JsonResponse(['error' => 'Niepoprawne dane'], Response::HTTP_BAD_REQUEST);
        }

        if ($data['password'] !== $data['password_rep']) {
            return new JsonResponse(['error' => 'Hasła nie są takie same'], Response::HTTP_BAD_REQUEST);
        } else if (strlen($data['password']) < 8) {
            return new JsonResponse(['error' => 'Hasło jest za krótkie'], Response::HTTP_BAD_REQUEST);
        }


        return $this->json([
            'register' => 'OK',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }

}
