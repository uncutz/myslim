<?php

namespace Backend;

use JsonException;
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


    public function getServerParams(): array
    {
        return $this->servers;
    }

    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    public function withCookieParams(array $cookies): self
    {
        if ($cookies === $this->cookies) {
            return $this;
        }

        $clone = clone $this;
        $clone->cookies = $cookies;

        return $clone;
    }

    public function getQueryParams(): array
    {

        if (empty($this->queries)) {
            $queries = [];
            parse_str($this->getUri()->getQuery(), $queries);
            return $queries;
        }
        return $this->queries;
    }

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
     * @throws JsonException
     */
    public function getParsedBody()
    {

        if($this->parsedBody !== null) {
            return $this->parsedBody;
        }
        if ($this->isPost()) {
            return $_POST;
        }

        if($this->inHeader('content-type', 'application/json')){
            return json_decode($this->getBody(), true, 512, JSON_THROW_ON_ERROR);
        }

        return $this->body;
    }

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

    public function withParsedBody($data): self
    {
        //TODO evtl unvollständig
        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): self
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    public function withoutAttribute($name): self
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);
        return $clone;
    }
}