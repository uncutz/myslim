<?php declare(strict_types=1);

namespace Backend\Factory;

use Backend\Http\Stream;
use InvalidArgumentException;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class StreamFactory implements StreamFactoryInterface
{

    /**
     * @param string $content
     * @return StreamInterface
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return new Stream($content);
    }

    /**
     * @param string $filename
     * @param string $mode
     * @return StreamInterface
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {

        if (Stream::validMode($mode)) {
            throw new InvalidArgumentException('argument 2 must be a valid mode');
        }

        $body = fopen($filename, $mode);

        if ($body === false) {
            throw new RuntimeException('file cannot be read');
        }

        return new Stream($body);

    }

    /**
     * @param resource $resource
     * @return StreamInterface
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        if (is_resource($resource)) {
            return new Stream($resource);
        }
        throw new InvalidArgumentException('argument 1 must be of type resource');
    }
}