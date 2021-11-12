<?php declare(strict_types=1);

namespace Backend\Http;

use Psr\Http\Message\ServerRequestInterface;

class ServerRequest implements ServerRequestInterface
{
    use MessageTrait;
    use RequestTrait;

    private array $servers;
    private array $cookies;
    private array $queries;
    private $parsedBody;
    private array $attributes;

    /**
     * @param string $method
     * @param $uri
     * @param array $headers
     * @param array $servers
     * @param array $cookies
     * @param array $attributes
     * @param string|null $body
     * @param string $version
     */
    public function __construct(
        string $method,
               $uri,
        array  $servers = [],
        array  $headers = [],
        array  $cookies = [],
        array  $attributes = [],
        string $body = null,
        string $version = '1.1'
    )
    {
        $this->method = strtolower($method);
        $this->protocol = $version;
        $this->servers = $servers;
        $this->cookies = $cookies;
        $this->attributes = $attributes;
        $this->setUri($uri);
        $this->setHeaders($headers);
        $this->setBody($body);
    }


    /**
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->servers;
    }

    /**
     * @return array
     */
    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     * @return $this
     */
    public function withCookieParams(array $cookies): self
    {
        if ($cookies === $this->cookies) {
            return $this;
        }

        $clone = clone $this;
        $clone->cookies = $cookies;

        return $clone;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {

        if (empty($this->queries)) {
            $queries = [];
            parse_str($this->getUri()->getQuery(), $queries);
            return $queries;
        }
        return $this->queries;
    }

    /**
     * @param string $param
     * @param null $default
     * @return mixed|null
     */
    public function getQueryParam(string $param, $default = null): string
    {
        return $this->getQueryParams()[$param] ?? $default;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function withQueryParams(array $query): ServerRequest
    {
        if ($query === $this->queries) {
            return $this;
        }
        $clone = clone $this;
        $clone->queries = $query;

        return $clone;
    }

    public function getUploadedFiles()
    {
        // TODO: Implement getUploadedFiles() method.
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        // TODO: Implement withUploadedFiles() method.
    }


    /**
     * @return array|mixed|object|\Psr\Http\Message\StreamInterface|null
     */
    public function getParsedBody()
    {

        if ($this->parsedBody !== null) {
            return $this->parsedBody;
        }
        if ($this->isPost()) {
            return $_POST;
        }

        if ($this->inHeader('content-type', 'application/json')) {
            return json_decode($this->getBody()->getContents(), true);
        }

        return $this->body;
    }

    /**
     * @return bool
     */
    private function isPost(): bool
    {
        $postHeaders = ['application/x-www-form-urlencoded', 'multipart/form-data'];
        $headerValues = $this->getHeader('content-type');
        foreach ($headerValues as $value) {
            if (in_array($value, $postHeaders, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array|object|null $data
     * @return $this
     */
    public function withParsedBody($data): self
    {
        //TODO evtl unvollständig
        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function withAttribute($name, $value): self
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withoutAttribute($name): self
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);
        return $clone;
    }
}