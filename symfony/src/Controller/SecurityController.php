<?php

namespace App\Controller;

use App\Entity\Worker;
use App\Enum\CookieVariant;
use App\Service\SecurityService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{


    public function __construct()
    {
    }


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

    #[Route('/api/workerLogin', name: 'worker_login', methods: ['POST'])]
    public function workerLogin(Request $request, EntityManagerInterface $em, Connection $connection): JsonResponse
    {
        $response = new JsonResponse();

        if (SecurityService::updateAuthCookie(CookieVariant::WORKER, $request, $response, $em)) {
            return $response->setData(['message' => 'Login successfull.']);
        }

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

        SecurityService::createAuthCookie($nick, CookieVariant::WORKER, $request, $response, $em);
        return $response->setData(['message' => 'Login successfull.']);
    }


    #[Route('/api/logout', name: 'logout', methods: ['GET'])]
    public function logout(Request $request): JsonResponse
    {
        return $this->json([
            'logout' => 'OK',
            'path' => 'src/Controller/SecurityController.php',
        ]);
    }

    private function destroyCookies(Response &$response, string $cookieNameHTTPOnly, string $cookieName): void
    {
        $response->headers->clearCookie(
            $cookieNameHTTPOnly,
            '/',
            null,
            true,
            true,
            'Strict'
        );
        $response->headers->clearCookie(
            $cookieName,
            '/',
            null,
            true,
            false,
            'Strict'
        );
    }

    #[Route('/api/workerLogout', name: 'worker_logout', methods: ['GET'])]
    public function workerLogout(Request $request, Connection $connection, EntityManagerInterface $em): JsonResponse
    {
        $response = new JsonResponse();
        SecurityService::destroyAuthCookie(CookieVariant::WORKER, $request, $response, $em);
        return $response->setData(['message' => 'Logout successfull.']);

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
