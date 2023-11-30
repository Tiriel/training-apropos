<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\OmdbApiConsumer;
use App\Movie\Search\SearchType;
use App\Movie\Search\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{
    public function __construct(
        protected readonly OmdbApiConsumer $consumer,
        protected readonly EntityManagerInterface $manager,
        protected readonly OmdbToMovieTransformer $transformer,
        protected readonly GenreProvider $genreProvider,
    ) {}

    public function getOne(SearchType $type, string $value): Movie
    {
        $data = $this->consumer->fetch($type, $value);

        $movie = $this->manager
            ->getRepository(Movie::class)
            ->findOneBy(['title' => $data[OmdbToMovieTransformer::TITLE_KEY]]);
        if ($movie instanceof  Movie) {
            return $movie;
        }

        $movie = $this->transformer->getOne($data);
        foreach ($this->genreProvider->getGenresFromOmdb($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
