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
}
