<?php declare(strict_types=1);

namespace Http;

class HttpRequest
{
    public static function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getPath() {
        return $_SERVER['REQUEST_URI'];
    }
 }