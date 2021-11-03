<?php declare(strict_types=1);

namespace Backend;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

//TODO https://www.youtube.com/watch?v=6VAAyuVsDco 00:40:30

class Uri implements UriInterface
{

    private const SCHEME_PORTS = ['http' => 80, 'https' => 443];
    private const SUPPORTED_SCHEMES = ['http', 'https'];

    /** @var string */
    private $scheme;
    /** @var string */
    private $user;
    /** @var string|null */
    private $password;
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


    /**
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $uriParts = parse_url($uri);
        $this->scheme = $uriParts['schema'];
        $this->host = strtolower($uriParts['host'] ?? '');
        $this->user = $uriParts['user'] ?? '';
        $this->password = $uriParts['password'] ?? null;
        $this->path = $uriParts['path'];
        $this->query = $uriParts['query'] ?? '';
        $this->setPort($this->scheme, $uriParts['port'] ?? null);
        $this->fragment = $uriParts['fragment'] ?? '';
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
        $userInfo = $this->user;
        if ($this->password !== null && $this->password !== '') {
            return $userInfo . ':' . $this->password;
        }
        return $userInfo;
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
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme): self
    {
        if ($this->scheme === $scheme) {
            return $this;
        }

        if (in_array($scheme, self::SUPPORTED_SCHEMES)) {
            throw new InvalidArgumentException('unsupported scheme');
        }


        $clone = clone $this;
        $clone->scheme = $scheme;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null): self
    {
        $clone = clone $this;
        $clone->user = $user;
        $clone->password = $password;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHost($host): self
    {
        if (is_string($host)) {
            throw new InvalidArgumentException('invalid host');
        }

        if ($host === strtolower($this->host)) {
            return $this;
        }

        $clone = clone $this;
        $clone->host = strtolower($host);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withPort($port): self
    {
        // TODO: Implement withPort() method.
    }

    /**
     * @inheritDoc
     */
    public function withPath($path): self
    {
        // TODO: Implement withPath() method.
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query): self
    {
        // TODO: Implement withQuery() method.
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment): self
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