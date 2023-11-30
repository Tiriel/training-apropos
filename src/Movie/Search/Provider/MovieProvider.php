<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\SearchType;

class MovieProvider
{
    public function getOne(SearchType $type, string $value): Movie
    {
        // fetch data from OMDb
        // check if movie already in DB
        // if yes, return movie
        // if no, transform data from OMDb
        // persist movie in DB
        //return movie
    }
}
