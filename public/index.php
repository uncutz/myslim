<?php

use Backend\Http\ServerRequest;
use Backend\Http\Uri;


require __DIR__ . '/../vendor/autoload.php';
//TODO https://www.youtube.com/watch?v=3Wuzs9V60x8 wie man psr7 anwendet


$uri = new Uri(
    sprintf('%s://%s%s%s',
        $_SERVER['REQUEST_SCHEME'],
        $_SERVER['HTTP_HOST'],
        $_SERVER['REQUEST_URI'],
        $_SERVER['QUERY_STRING'] === '' ? '' : '?' . $_SERVER['QUERY_STRING'],
    )
);
$request = new ServerRequest(
    $_SERVER['REQUEST_METHOD'],
    $uri,
    getallheaders(),
    $_SERVER,
    $_COOKIE,
);



