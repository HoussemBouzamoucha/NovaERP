<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException as SecurityAccessDeniedException;

class ClearTargetPathSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // If access denied for admin-only paths, clear saved target path to avoid redirect loops
        if (str_starts_with($path, '/users') || str_starts_with($path, '/admin')) {
            $exception = $event->getThrowable();
            if ($exception instanceof SecurityAccessDeniedException) {
                $session = $request->getSession();
                if ($session && $session->has('_security.main.target_path')) {
                    $session->remove('_security.main.target_path');
                    $this->logger->info('Cleared saved target_path for path to prevent redirect loop', ['path' => $path]);
                }
            }
        }
    }
}
