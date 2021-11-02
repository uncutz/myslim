<?php declare(strict_types=1);

namespace Backend;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{

    /** @var string */
    private $method;

    /** @var string */
    private $header;

    /** @var string */
    private $body;

    /** @var Uri */
    private $uri;

    /**
     * @param string $method
     * @param string $header
     * @param string $body
     * @param Uri $uri
     */
    public function __construct(string $method, string $header, string $body, Uri $uri)
    {
        $this->method = $method;
        $this->header = $header;
        $this->body = $body;
        $this->uri = $uri;
    }


    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }

    public function getProtocolVersion()
    {
        // TODO: Implement getProtocolVersion() method.
    }

    public function withProtocolVersion($version)
    {
        // TODO: Implement withProtocolVersion() method.
    }


    public function getHeaders(): array
    {
        $header = array();
        foreach (getallheaders() as $name => $values) {
            $header[] = $name . ": " . implode(", ", $values);
        }
        return $header;

    }

    /**
     * @param $name
     * @return bool
     */
    public function hasHeader($name): bool
    {
        return strcasecmp($name, $this->header) === 1;
    }

    /**
     * @param string $name
     * @return array<string, string>
     */
    public function getHeader($name): array
    {

        $headerValues = array();
        foreach (getallheaders() as $header => $values) {
            if ($header === $name) {
                foreach ($values as $value) {
                    $headerValues[] = $value;
                }
                break;
            }
        }
        if (!$headerValues) {
            return [];
        }
        return $headerValues;
    }

    public function getHeaderLine($name)
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader($name, $value)
    {
        // TODO: Implement withHeader() method.
    }

    public function withAddedHeader($name, $value)
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader($name)
    {
        // TODO: Implement withoutHeader() method.
    }

    /**
     * @return StreamInterface|string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }

    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    /**
     * @return Uri
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }
}