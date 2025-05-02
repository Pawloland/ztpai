<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PosterFallbackSubscriber implements EventSubscriberInterface
{
    private string $projectDir;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        string $projectDir
    )
    {
        $this->projectDir = $projectDir;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();

        if (preg_match('#^/uploads/posters/(.+)$#', $pathInfo, $matches)) {
            $posterName = $matches[1];
            $posterPath = $this->projectDir . '/public/uploads/posters/' . $posterName;
            $defaultPosterPath = $this->projectDir . '/public/uploads/posters/blank-poster.png';

            if (!file_exists($posterPath)) {
                $response = new BinaryFileResponse($defaultPosterPath);
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, 'blank-poster.png');
                $response->headers->set('Content-Type', 'image/png'); // Set correct content type
                $response->setStatusCode(200); // Explicitly set the status code to 200
                $event->setResponse($response);
                $event->stopPropagation(); // Stop further event propagation
                $response->send(); // Ensure the file content is sent
            }
        }
    }
}