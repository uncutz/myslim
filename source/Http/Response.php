<?php declare(strict_types=1);

namespace Backend\Http;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{

    use MessageTrait;

    /** @var int */
    private int $code;

    private const REASON_PHRASE = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        404 => 'Not Found'
        //TODO Add all status code reason phrase
    ];

    /**
     * @param int $code
     * @param null $body
     * @param array $headers
     * @param string $version
     */
    public function __construct(int $code, $body = null,array $headers = [], string $version = '1.1')
    {
        $this->code = $code;
        $this->setBody($body);
        $this->setHeaders($headers);
        $this->protocol = $version;
    }


    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return $this
     */
    public function withStatus($code, $reasonPhrase = ''): self
    {

        if (!is_int($code)) {
            throw new InvalidArgumentException('argument 1 must be of type int');
        }
        if ($this->code = $code) {
            return $this;
        }

        $clone = clone $this;
        $clone->code = $code;

        return $clone;
    }

    /**
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return self::REASON_PHRASE[$this->code] ?? '';
    }
}