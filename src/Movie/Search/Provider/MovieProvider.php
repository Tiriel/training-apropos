<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\OmdbApiConsumer;
use App\Movie\Search\SearchType;
use App\Movie\Search\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    protected ?SymfonyStyle $io = null;

    public function __construct(
        protected readonly OmdbApiConsumer $consumer,
        protected readonly EntityManagerInterface $manager,
        protected readonly OmdbToMovieTransformer $transformer,
        protected readonly GenreProvider $genreProvider,
    ) {
    }

    public function getOne(SearchType $type, string $value): Movie
    {
        $this->io?->text('Fetching data from OMDb...');
        $data = $this->consumer->fetch($type, $value);

        $movie = $this->manager
            ->getRepository(Movie::class)
            ->findOneBy(['title' => $data[OmdbToMovieTransformer::TITLE_KEY]]);
        if ($movie instanceof  Movie) {
            $this->io?->note('Movie already in database!');
            return $movie;
        }

        $this->io?->text('Creating movie object...');
        $movie = $this->transformer->getOne($data);
        foreach ($this->genreProvider->getGenresFromOmdb($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->io?->text('Saving in database.');
        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }

    public function setIo(SymfonyStyle $io)
    {
        $this->io = $io;
    }
}
