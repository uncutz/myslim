<?php declare(strict_types=1);

namespace Backend;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;


class Uri implements UriInterface
{

    private const SCHEME_PORTS = ['http' => 80, 'https' => 443];
    private const SUPPORTED_SCHEMES = ['http', 'https'];

    /** @var string */
    private string $scheme; //http / https
    /** @var string */
    private string $user;
    /** @var string|null */
    private ?string $password;
    /** @var string */
    private string $host;
    /** @var string */
    private string $path; //der relative Pfad
    /** @var int|null */
    private ?int $port; //Port
    /** @var string */
    private string $query; //?parameter
    /** @var string */
    private string $fragment; //#fragment


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
        $this->setPort($uriParts['port'] ?? null);
        $this->fragment = $uriParts['fragment'] ?? '';
    }

    private function setPort(?int $port): void
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
    public function getPath(): string
    {
        $path = trim($this->path, '/');
        return '/' . $path;
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
        if ($port === $this->port) {
            return $this;
        }
        $clone = clone $this;
        $clone->setPort($port);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withPath($path): self
    {

        if (is_string($path)) {
            throw new InvalidArgumentException('invalid path');
        }

        if ($path === $this->path) {
            return $this;
        }
        $clone = clone $this;
        $clone->path = $path;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query): self
    {

        if (is_string($query)) {
            throw new InvalidArgumentException('invalid query');
        }

        if ($query === $this->query) {
            return $this;
        }
        $clone = clone $this;
        $clone->path = $query;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment): self
    {
        if (is_string($fragment)) {
            throw new InvalidArgumentException('invalid fragment');
        }

        if ($fragment === $this->$fragment) {
            return $this;
        }
        $clone = clone $this;
        $clone->path = $fragment;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {

        $query = '';
        if ($this->query !== '') {
            $query = '?' . $this->query;
        }

        $fragment = '';
        if ($this->fragment !== '') {
            $fragment = '#' . $this->fragment;
        }

        return sprintf('%s//:%s%s%s%s',
            $this->getScheme(),
            $this->getAuthority(),
            $this->getPath(),
            $query,
            $fragment
        );

    }
}