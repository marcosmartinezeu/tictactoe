<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class ExceptionSubscriber implements EventSubscriberInterface
{
    private $httpErrorCodes = [500, 404, 400, 412];

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        $exceptionData = [
            'error' => true,
            'message' => $exception->getMessage()
        ];

        $statusCode = !in_array($exception->getCode(), $this->httpErrorCodes) ? 500 : $exception->getCode();

        $response = new JsonResponse($exceptionData, $statusCode);
        $event->setResponse($response);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
