<?php

namespace App\Movie\Search;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[When(env: 'prod')]
#[AsDecorator(OmdbApiConsumer::class)]
class CacheableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        protected readonly OmdbApiConsumer $inner,
        protected readonly CacheInterface $cache,
        protected readonly SluggerInterface $slugger
    ) {}

    public function fetch(SearchType $type, string $value): array
    {
        $key = sprintf("%s_%s", $type->value, $value);

        return $this->cache->get(
            $key,
            fn() => $this->inner->fetch($type, $value),
        );
    }
}
