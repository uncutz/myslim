<?php declare(strict_types=1);

namespace Backend;

class Route
{
    /** @var string */
    private string $type;
    /** @var string */
    private string $path;
    /** @var string */
    private string $class;

    /**
     * @param string $type
     * @param string $path
     * @param string $class
     */
    public function __construct(string $type, string $path, string $class)
    {
        $this->type = $type;
        $this->path = $path;
        $this->class = $class;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

}