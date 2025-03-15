<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{
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
    public function adminLogin(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['nick']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Niepoprawne dane'], Response::HTTP_BAD_REQUEST);
        }


        return $this->json([
            'adminLogin' => 'OK',
            'path' => 'src/Controller/SecurityController.php',
        ]);
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
    public function adminLogout(Request $request): JsonResponse
    {
        return $this->json([
            'logout' => 'OK',
            'path' => 'src/Controller/SecurityController.php',
        ]);
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
