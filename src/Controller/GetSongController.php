<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AutoconfigureTag('controller.service_arguments')]
class GetSongController
{
    public function __construct(
        private readonly Environment $twig
    ) {}

    #[Route('/song', name: 'app_get_song', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('get_songs.html.twig', [
            'controller_name' => 'GetSongController',
        ]));
    }
}
