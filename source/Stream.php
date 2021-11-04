<?php

namespace Backend;

use http\Exception\InvalidArgumentException;
use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{

    /** @var resource|null */
    private $stream;

    private ?int $size;

    public function __construct($body = null)
    {
        if (!is_string($body) && !is_resource($body) && $body !== null) {
            throw new InvalidArgumentException('argument 1 must be string, ressource or null');
        }

        if (!is_string($body)) {
            $resource = fopen('php://temp', 'wb+');
            fwrite($resource, $body);
            $body = $resource;
        }
        $this->stream = $body;


    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
    }

    /**
     *
     */
    public function close(): void
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
        $this->detach();
    }

    /**
     * @return resource|null
     */
    public function detach(): ?string
    {
        $resource = $this->stream;
        unset($this->stream);
        return $resource;
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int
    {
        $stats = fstat($this->stream);

        return $stats['size'] ?? null;
    }

    public function tell()
    {
        // TODO: Implement tell() method.
    }

    public function eof()
    {
        // TODO: Implement eof() method.
    }

    public function isSeekable()
    {
        // TODO: Implement isSeekable() method.
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        // TODO: Implement seek() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    public function isWritable()
    {
        // TODO: Implement isWritable() method.
    }

    public function write($string)
    {
        // TODO: Implement write() method.
    }

    public function isReadable()
    {
        // TODO: Implement isReadable() method.
    }

    public function read($length)
    {
        // TODO: Implement read() method.
    }

    public function getContents()
    {
        // TODO: Implement getContents() method.
    }

    public function getMetadata($key = null)
    {
        // TODO: Implement getMetadata() method.
    }
}