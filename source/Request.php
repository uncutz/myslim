<?php declare(strict_types=1);

namespace Backend;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{

    use MessageTrait;

    private const VALID_METHODS = ['get', 'post', 'delete', 'put', 'patch', 'head', 'option'];

    /** @var string */
    private string $requestTarget;
    /** @var string */
    private string $method;
    /** @var UriInterface */
    private UriInterface $uri;

    /**
     * @param string $method
     * @param Uri $uri
     * @param array $headers
     * @param string|null $body
     * @param string $version
     */
    public function __construct(string $method, $uri, array $headers = [], string $body = null, string $version = '1.1')
    {
        $this->method = strtolower($method);
        $this->protocol = $version;
        $this->setHeaders($headers);
        $this->setBody($body);
        $this->setUri($uri);
    }


    /**
     * @param $uri
     */
    protected function setUri($uri): void
    {
        if (!is_string($uri)) {
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
        $method = strtolower($method);

        if ($method === $this->method) {
            return $this;
        }

        if (in_array($method, self::VALID_METHODS)) {
            throw new \InvalidArgumentException('Unknown method! Allowed methods: ' .
                implode(', ', self::VALID_METHODS));
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

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;

        if ($preserveHost) {
            $newUri = $uri->withHost($this->uri->getHost());
        }

        $clone->uri = $uri;
    }

}