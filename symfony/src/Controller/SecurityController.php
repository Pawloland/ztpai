<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Worker;
use App\Enum\CookieVariant;
use App\Service\RabbitmqService;
use App\Service\SecurityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $response = new JsonResponse();

        if (SecurityService::updateAuthCookie(CookieVariant::CLIENT, $request, $response, $em)) {
            return $response->setData(['message' => 'Login successful.']);
        }

        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Missing credentials.'], Response::HTTP_BAD_REQUEST);
        }

        $client = $em->getRepository(Client::class)->findOneBy(['mail' => $email]);
        if (!$client || !password_verify($password, $client->getPasswordHash())) {
            return new JsonResponse(['error' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        SecurityService::createAuthCookie($email, CookieVariant::CLIENT, $request, $response, $em);
        return $response->setData(['message' => 'Login successful.']);
    }

    #[Route('/api/workerLogin', name: 'worker_login', methods: ['POST'])]
    public function workerLogin(Request $request, EntityManagerInterface $em): JsonResponse
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
    public function logout(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $response = new JsonResponse();
        SecurityService::destroyAuthCookie(CookieVariant::CLIENT, $request, $response, $em);

        $request->attributes->remove(CookieVariant::CLIENT->name);

        return $response->setData(['message' => 'Logout successfull.']);
    }

    #[Route('/api/workerLogout', name: 'worker_logout', methods: ['GET'], defaults: ['_skip_auth' => true])]
    public function workerLogout(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $response = new JsonResponse();
        SecurityService::destroyAuthCookie(CookieVariant::WORKER, $request, $response, $em);

        $request->attributes->remove(CookieVariant::WORKER->name);

        return $response->setData(['message' => 'Logout successfull.']);

    }


    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password']) || strlen($data['password']) < 8) {
            return new JsonResponse(['error' => 'Invalid data or password too short'], Response::HTTP_BAD_REQUEST);
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $client = new Client()
            ->setClientName('')
            ->setClientSurname('')
            ->setNick($data['email'])
            ->setPasswordHash($hashedPassword)
            ->setMail($data['email']);

        try {
            $em->persist($client);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while saving the client.'], Response::HTTP_CONFLICT);
        }

        return new JsonResponse(['message' => 'Registration successful'], Response::HTTP_CREATED);
    }

}
