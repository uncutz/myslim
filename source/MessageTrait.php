<?php

namespace Backend;

//TODO https://www.youtube.com/watch?v=QHPmA5LFtrM 00:23:00
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

trait MessageTrait //gemeinsame methoden für request und response
{

    private string $protocol = "1.1";
    private array $headers = [];
    /** @var StreamInterface|null  */
    private ?StreamInterface $body;

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function withProtocolVersion($version): self
    {
        if ($this->protocol === $version) {
            return $this;
        }

        $clone = clone $this;
        $clone->protocol = $version;

        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        $name = strtolower($name);
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        $name = strtolower($name);

        if (!$this->hasHeader($name)) {
            return [];
        }

        return $this->headers[$name];
    }

    public function getHeaderLine($name): string
    {
        $values = $this->getHeader($name);
        return implode(',', $values);
    }

    public function withHeader($name, $value): self
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException('argument 1 must be a string');
        }

        if (!is_string($name) && !is_array($value)) {
            throw new InvalidArgumentException('argument 1 must be a string');
        }

        $name = strtolower($name);
        if (is_string($value)) {
            $value = [$value];
        }

        $clone = clone $this;
        $clone->headers[$name] = $value;

        return $clone;
    }

    public function withAddedHeader($name, $value): self
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException('argument 1 must be a string');
        }

        if (!is_string($name) && !is_array($value)) {
            throw new InvalidArgumentException('argument 1 must be a string');
        }

        $name = strtolower($name);
        if (is_string($value)) {
            $value = [$value];
        }

        $clone = clone $this;
        $clone->headers[$name] = array_merge($clone->headers[$name], $value); //im array_merge [$name] hinzugefügt,
        // wenn es nicht funktioniert, dann weglassen

        return $clone;
    }

    public function withoutHeader($name): self
    {
        $clone = clone $this;
        unset($clone->headers[$name]);
        return $clone;
    }

    public function getBody(): StreamInterface
    {
        if ($this->body === null) {
            throw new RuntimeException('Could not find Instance of StreamInterface');
        }
        return $this->body;
    }

    public function withBody(StreamInterface $body): self
    {
        if ($body === $this->body) {
            return $this;
        }

        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

}