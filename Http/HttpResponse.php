<?php

namespace Http;

use JsonException;

class HttpResponse
{

    /** @var int */
    private $statusCode;
    /** @var array<string, string> */
    private $headers;
    /** @var string */
    private $body;

    /**
     * @param int $statusCode
     * @param array $headers
     * @param string|null $body
     */
    public function __construct(int $statusCode = 200, array $headers = [], string $body = null)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }


    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }



}