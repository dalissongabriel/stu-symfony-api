<?php


namespace App\EventListeners;


use App\Helper\EntityFactoryException;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handle404Exception',2],
                ['handleEntityException',1],
                ['handleEntityNotFoundException',0]
            ]
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {

            $responseFactory = ResponseFactory::fromError(
                $event->getThrowable(),
                Response::HTTP_NOT_FOUND);

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handleEntityException(ExceptionEvent $event) {

        $exception = $event->getThrowable();

        if ($exception instanceof  EntityFactoryException) {
            $responseFactory = ResponseFactory::fromError(
                $exception,
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handleEntityNotFoundException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof  EntityNotFoundException) {
            $responseFactory = ResponseFactory::fromError(
                $exception,
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }
}