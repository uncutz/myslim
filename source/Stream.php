<?php declare(strict_types=1);

namespace Backend;

use http\Exception\InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Throwable;


class Stream implements StreamInterface
{

    /** @var string[][] */
    private const READ_WRITE_MODE = [
        'read' => ['r', 'r+', 'w+', 'a+', 'x+', 'c+'],
        'write' => ['w', 'r+', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+']
    ];

    /** @var resource|null */
    private $stream;
    /** @var int|null */
    private ?int $size;
    /** @var bool */
    private bool $seekable;
    /** @var bool */
    private bool $writable;
    /** @var bool */
    private bool $readable;


    public function __construct($body = null)
    {
        if (!is_string($body) && !is_resource($body) && $body !== null) {
            throw new InvalidArgumentException('argument 1 must be string, resource or null');
        }

        if (is_string($body)) {
            $resource = fopen('php://temp', 'wb+');
            fwrite($resource, $body);
            $body = $resource;
        }
        $this->stream = $body;
        $this->seekable = false;
        $this->writable = false;
        $this->readable = false;

        if ($this->isSeekable()) {
            fseek($body, 0, SEEK_CUR);
        }


    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            if ($this->isSeekable()) {
                $this->rewind();
            }
            return $this->getContents();
        } catch (Throwable $exception) {
            return '';
        }
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
        if ($this->seekable === null) {
            $this->seekable = $this->getMetadata('seekable') ?? false;
        }

        return $this->seekable;
    }

    /**
     * @param int $offset
     * @param int $whence
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new RuntimeException('stream is not seekable');
        }

        if (fseek($this->stream, $offset, $whence)) {
            throw new RuntimeException('unable to seek stream position' . $offset);
        }
    }


    public function rewind(): void
    {
        if (!$this->isSeekable()) {
            throw new RuntimeException('stream is not seekable');
        }

        $this->seek(0);
    }

    /**
     * @return bool
     */
    public function isWritable(): bool
    {

        if (!is_resource($this->stream)) {
            return false;
        }
        if ($this->writable === null) {
            $mode = $this->getMetadata('mode');
            $this->writable = in_array($mode, self::READ_WRITE_MODE['write'], false);
        }

        return $this->writable;
    }

    /**
     * @param string $string
     * @return int
     */
    public function write($string): int
    {
        if (!$this->isWritable()) {
            throw new RuntimeException('string is not writeable');
        }
        $result = fwrite($this->stream, $string);
        if ($result === false) {
            throw new RuntimeException('unable to write to stream');
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isReadable(): bool
    {
        if (!is_resource($this->stream)) {
            return false;
        }
        if ($this->readable === null) {
            $mode = $this->getMetadata('mode');
            $this->readable = in_array($mode, self::READ_WRITE_MODE['read'], false);
        }

        return $this->readable;
    }

    /**
     * @param int $length
     * @return string
     */
    public function read($length): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException('string is not readable');
        }
        $result = fread($this->stream, $length);
        if ($result === false) {
            throw new RuntimeException('unable to read stream');
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        if (!is_resource($this->stream)) {
            throw new RuntimeException('unable to read stream contents');
        }
        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new RuntimeException('unable to read stream contents');
        }
        return $contents;
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
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