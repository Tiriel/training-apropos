<?php

namespace App\Movie\Search\Transformer;

use App\Entity\Genre;

class OmdbToGenreTransformer
{
    public function getOne(string $data): Genre
    {
        if (str_contains($data, ', ')) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($data);
    }

    public function getGenresFromOmdb(string $data): iterable
    {
        foreach (explode(', ', $data) as $name) {
            yield $this->getOne($name);
        }
    }
}
