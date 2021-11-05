<?php

namespace Backend;

use http\Exception\InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

//TODO https://www.youtube.com/watch?v=6VAAyuVsDco 01:17:00
class Stream implements StreamInterface
{

    /** @var resource|null */
    private $stream;
    /** @var int|null */
    private ?int $size;
    /** @var bool */
    private bool $seekable;

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

        if($this->isSeekable()) {
            fseek($body, 0 ,SEEK_CUR);
        }


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
        if ($this->size !== null) {
            return $this->size;
        }

        if ($this->stream === null) {
            return null;
        }

        $stats = fstat($this->stream);
        $this->size = $stats['size'] ?? null;

        return $this->size;
    }

    /**
     * @return int
     */
    public function tell(): int
    {
        if ($this->stream === null) {
            throw new RuntimeException('unable to get current position');
        }
        $position = ftell($this->stream);

        if ($position === false) {
            throw new RuntimeException('unable to get current position');
        }

        return $position;
    }

    /**
     * @return bool
     */
    public function eof(): bool
    {
        return $this->stream !== null && feof($this->stream);
    }

    public function isSeekable(): bool
    {
        if($this->seekable === null) {
            $this->seekable = $this->getMetadata('seekable') ?? false;
        }

        return $this->seekable;
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
        if ($this->stream === null) {
            return $key === null ? null : [];
        }

        $meta = stream_get_meta_data($this->stream);
        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }
}