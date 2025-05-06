<?php

namespace App\EventSubscriber;

use App\Enum\CookieVariant;
use App\Service\SecurityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthenticationCookieUpdateSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        foreach ([CookieVariant::CLIENT, CookieVariant::WORKER] as $variant) {
            $resp_set_as_attribute = $request->attributes->get($variant->name);

            if ($resp_set_as_attribute instanceof JsonResponse) {
                // Copy all cookies from temporary response
                foreach ($resp_set_as_attribute->headers->getCookies() as $cookie) {
                    $response->headers->setCookie($cookie);
                }
            }
        }
    }
}
