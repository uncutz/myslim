<?php

use Backend\Factory\RequestFactory;
use Backend\Http\ServerRequest;
use Backend\Http\Uri;


require __DIR__ . '/../vendor/autoload.php';
//TODO https://www.youtube.com/watch?v=3Wuzs9V60x8 wie man psr7 anwendet


$uri = new Uri(
    sprintf('%s://%s%s',
        $_SERVER['REQUEST_SCHEME'],
        $_SERVER['HTTP_HOST'],
        $_SERVER['REQUEST_URI']
    )
);

$request = new ServerRequest(
    $_SERVER['REQUEST_METHOD'],
    $uri,
    getallheaders(),
    $_SERVER,
    $_COOKIE,
);


//alternative
$requestFactory = new RequestFactory();
$request = $requestFactory->createRequest($_SERVER['REQUEST_METHOD'], $uri);
var_dump($request);


//TODO Script schreiben, welches anhand der Request den
// richtigen Controller instanziiert
// Anforderung:
// jeder Controller bekommt als Parameter
// (ServerRequest)$request und
// (Response)$response,
// array $args (query params)

