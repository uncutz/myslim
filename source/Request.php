<?php declare(strict_types=1);

namespace Backend;

use Psr\Http\Message\RequestInterface;

class Request implements RequestInterface
{

    use MessageTrait;
    use RequestTrait;

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

}