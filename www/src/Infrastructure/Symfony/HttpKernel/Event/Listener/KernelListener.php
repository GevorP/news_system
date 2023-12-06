<?php

namespace App\Infrastructure\Symfony\HttpKernel\Event\Listener;

use App\Infrastructure\Symfony\HttpKernel\Exception\ErrorsAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class KernelListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $exception = $event->getThrowable();


        $response = new JsonResponse([
            'errors' => $exception instanceof ErrorsAwareInterface ? $exception->getErrors() : [],
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTrace(),
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        // $this->logger->error($exception->getMessage(), $exception->getTrace());

        $event->setResponse($response);
    }
}
