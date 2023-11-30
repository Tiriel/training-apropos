<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Movie\Search\OmdbApiConsumer;
use App\Movie\Search\SearchType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show')]
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/omdb/{title}', name: 'app_movie_omdb',methods: ['GET'])]
    public function omdb(string $title, OmdbApiConsumer $consumer): Response
    {
        dd($consumer->fetch(SearchType::Title, $title));
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    #[Route('/{id<\d+>}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function save(Request $request, ?Movie $movie, EntityManagerInterface $manager): Response
    {
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId()]);
        }

        return $this->render('movie/new.html.twig', [
            'form' => $form,
            'movie' => $movie,
        ]);
    }

    #[Route('/_last_movies', name: 'app_movie_lastmovies', methods: ['GET'])]
    public function lastMovies(MovieRepository $repository): Response
    {
        return $this->render('includes/_last_movies.html.twig', [
            'last_movies' => $repository->findBy([], ['id' => 'DESC'], 5),
        ])->setTtl(3600);
    }
}
