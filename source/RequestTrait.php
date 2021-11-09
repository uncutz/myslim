<?php declare(strict_types=1);

namespace Backend;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

Trait RequestTrait
{
    private array $validMethods = ['get', 'post', 'delete', 'put', 'patch', 'head', 'option'];

    /** @var string */
    protected string $requestTarget;
    /** @var string */
    protected string $method;
    /** @var UriInterface */
    protected UriInterface $uri;


    /**
     * @param $uri
     */
    protected function setUri($uri): void
    {
        if (is_string($uri)) {
            $uri = new Uri($uri);
        }

        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    /**
     * @param mixed $requestTarget
     * @return $this
     */
    public function withRequestTarget($requestTarget): self
    {
        if ($requestTarget === $this->requestTarget) {
            return $this;
        }

        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function withMethod($method): self
    {
        if (!is_string($method)) {
            throw new InvalidArgumentException('argument must be of type string');
        }
        $method = strtolower($method);

        if ($method === $this->method) {
            return $this;
        }

        if (in_array($method, $this->validMethods, true)) {
            throw new InvalidArgumentException('Unknown method! Allowed methods: ' .
                implode(', ', $this->validMethods));
        }

        $clone = clone $this;
        $clone->method = $method;
        return $clone;
    }

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): void
    {
        $clone = clone $this;

        if ($preserveHost) {
            $newUri = $uri->withHost($this->uri->getHost());
        }

        $clone->uri = $uri;
    }

}