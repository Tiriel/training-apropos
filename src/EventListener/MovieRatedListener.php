<?php

namespace App\EventListener;

use App\Event\MovieRatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: MovieRatedEvent::class)]
class MovieRatedListener
{
    public function __invoke(MovieRatedEvent $event): void
    {
        $movie = $event->getMovie();
    }
}
