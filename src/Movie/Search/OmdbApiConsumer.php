<?php

namespace App\Movie\Search;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public function __construct(protected readonly HttpClientInterface $omdbClient) {}

    public function fetch(SearchType $type, string $value): array
    {
        $data = $this->omdbClient->request(
            'GET',
            '',
            ['query' => [
                'plot' => 'full',
                $type->value => $value,
            ]]
        )->toArray();

        if (\array_key_exists('Error', $data)) {
            if ('Movie not found!' === $data['Error']) {
                throw new NotFoundHttpException('Movie not found!');
            }

            throw new \InvalidArgumentException();
        }

        return $data;
    }
}
