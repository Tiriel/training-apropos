<?php

namespace App\Movie\Search\Provider;

use App\Entity\Genre;
use App\Movie\Search\Transformer\OmdbToGenreTransformer;
use App\Repository\GenreRepository;

class GenreProvider
{
    public function __construct(
        protected readonly GenreRepository $repository,
        protected readonly OmdbToGenreTransformer $transformer,
    ) {}

    public function getOne(string $name): Genre
    {
        return $this->repository->findOneBy(['name' => $name])
            ?? $this->transformer->getOne($name);
    }

    public function getGenresFromOmdb(string $data): iterable
    {
        foreach (explode(', ', $data) as $name) {
            yield $this->getOne($name);
        }
    }
}
