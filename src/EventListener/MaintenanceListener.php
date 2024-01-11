<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Twig\Environment;

#[AsEventListener(event: RequestEvent::class, priority: 9999)]
class MaintenanceListener
{
    public function __construct(
        private readonly Environment $twig,
        #[Autowire(param: 'app.is_maintenance')]
        private readonly bool $isMaintenance,
    ) {}

    public function __invoke(RequestEvent $event): void
    {
        if ($this->isMaintenance) {
            $response = new Response();
            if (HttpKernelInterface::MAIN_REQUEST === $event->getRequestType()) {
                $response->setContent($this->twig->render('maintenance.html.twig'));
            }

            $event->setResponse($response);
        }
    }
}
