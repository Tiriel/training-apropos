<?php

namespace App\Movie\Search\Transformer;

use App\Entity\Movie;

class OmdbToMovieTransformer
{
    public const TITLE_KEY = 'Title';
    public const KEYS = [
        self::TITLE_KEY,
        'Plot',
        'Country',
        'Released',
        'Year',
        'Poster',
    ];

    public function getOne(array $data): Movie
    {
        if (\count(\array_diff(self::KEYS, \array_keys($data))) > 0) {
            throw new \InvalidArgumentException();
        }

        $date = $data['Released'] === 'N/A' ? '01-01-'.$data['Year'] : $data['Released'];

        return (new Movie())
            ->setTitle($data['Title'])
            ->setPlot($data['Plot'])
            ->setCountry($data['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPoster($data['Poster'])
            ->setPrice(5.0)
            //->setRated($data['Rated'])
            //->setImdbId($data['imdbID'])
        ;
    }
}
