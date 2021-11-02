<?php declare(strict_types=1);

namespace Backend;

use Psr\Http\Message\UriInterface;

//TODO https://www.youtube.com/watch?v=6VAAyuVsDco 00:27:30

class Uri implements UriInterface
{

    /** @var string */
    private $scheme;
    /** @var string */
    private $user;
    /** @var string */
    private $host;
    /** @var string */
    private $path;
    /** @var int|null */
    private $port;
    /** @var string */
    private $query;
    /** @var string */
    private $fragment;

    private const SCHEME_PORTS = ['http' => 80, 'https' => 443];

    /**
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $uriParts = parse_url($uri);
        $this->scheme = $uriParts['schema'];
        $this->host = $uriParts['host'] ?? '';
        $this->user = $uriParts['user'] ?? '';
        $this->path = $uriParts['path'];
        $this->query = $uriParts['query'] ?? '';
        $this->setPort($this->scheme, $uriParts['port'] ?? null);
    }

    private function setPort(string $scheme, ?int $port)
    {
        if (self::SCHEME_PORTS[$this->scheme] === $port) {
            $this->port = null;

            return;
        }
        $this->port = $port;
    }


    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->getUserInfo() !== '') {
            $authority = $this->getUserInfo() . '@' . $this->host;
        }

        if ($this->getPort() !== null) {
            $authority = ':' . $this->port;
        }

        return $authority;
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo(): string
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getFragment()
    {
        // TODO: Implement getFragment() method.
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme)
    {
        // TODO: Implement withScheme() method.
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null)
    {
        // TODO: Implement withUserInfo() method.
    }

    /**
     * @inheritDoc
     */
    public function withHost($host)
    {
        // TODO: Implement withHost() method.
    }

    /**
     * @inheritDoc
     */
    public function withPort($port)
    {
        // TODO: Implement withPort() method.
    }

    /**
     * @inheritDoc
     */
    public function withPath($path)
    {
        // TODO: Implement withPath() method.
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query)
    {
        // TODO: Implement withQuery() method.
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment)
    {
        // TODO: Implement withFragment() method.
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        // TODO: Implement __toString() method.

    }
}