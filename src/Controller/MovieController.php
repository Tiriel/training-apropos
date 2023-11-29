<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show')]
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/_last_movies', name: 'app_movie_lastmovies', methods: ['GET'])]
    public function lastMovies(): Response
    {
        // db
        $lastMovies = [
            ['id' => 1, 'title' => 'Star Wars - Episode IV : A New Hope']
        ];

        return $this->render('includes/_last_movies.html.twig', [
            'last_movies' => $lastMovies,
        ])->setTtl(3600);
    }
}
