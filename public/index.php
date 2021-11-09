<?php

require __DIR__ . '/../vendor/autoload.php';
//TODO https://www.youtube.com/watch?v=3Wuzs9V60x8 wie man psr7 anwendet


var_dump(headers_list());
$uri = new \Backend\Uri(
    sprintf('%s://%s%s%s',
        $_SERVER['REQUEST_SCHEME'],
        $_SERVER['HTTP_HOST'],
        $_SERVER['REQUEST_URI'],
        $_SERVER['QUERY_STRING'] === '' ? '' : '?' . $_SERVER['QUERY_STRING'],
    )
);
$request = new \Backend\ServerRequest(
    $_SERVER['REQUEST_METHOD'],
    $uri,
    getallheaders(),
    $_SERVER,
    $_COOKIE,


);