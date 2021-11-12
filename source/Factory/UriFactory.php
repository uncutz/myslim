<?php declare(strict_types=1);

namespace Backend\Factory;

use Backend\Http\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{

    /**
     * @param string $uri
     * @return UriInterface
     */
    public function createUri(string $uri = ''): UriInterface
    {
        try {
            $newUri = new Uri($uri);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException('could not parse the given Uri');
        }
        return $newUri;
    }
}