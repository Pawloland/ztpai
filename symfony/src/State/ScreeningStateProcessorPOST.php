<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Screening;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ScreeningStateProcessorPOST implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface     $persistProcessor,
        private RequestStack           $requestStack,
        private ValidatorInterface     $validator,
        private EntityManagerInterface $em
    )
    {
    }


    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Screening
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new BadRequestHttpException("Request is missing");
        }

        if ($request->getMethod() !== Request::METHOD_POST) {
            throw new MethodNotAllowedHttpException(['POST'], 'Only POST method is allowed');
        }

        $this->em->getRepository(Screening::class)->addScreening($data);
        return $data;
    }
}
