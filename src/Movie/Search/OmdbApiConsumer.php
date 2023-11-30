<?php

namespace App\Movie\Search;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    protected readonly HttpClientInterface $client;

    public function fetch(SearchType $type, string $value): iterable
    {
        $data = $this->client->request(
            'GET',
            '',
            ['query' => [
                'plot' => 'full',
                $type->value => $value,
            ]]
        )->toArray();

        if (\array_key_exists('Error', $data) && 'Movie not found!' === $data['Error']) {
            throw new NotFoundHttpException('Movie not found!');
        }

        return $data;
    }
}
