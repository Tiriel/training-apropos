<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetSongsController extends AbstractController
{
    #[Route('/songs', name: 'app_get_songs')]
    public function __invoke(): Response
    {
        return $this->render('get_songs.html.twig', [
            'controller_name' => 'GetSongsController',
        ]);
    }
}
