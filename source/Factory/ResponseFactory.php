<?php declare(strict_types=1);

namespace Backend\Factory;

use Backend\Http\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return ResponseInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {

        $response = new Response($code);

        if ($reasonPhrase !== null && $reasonPhrase !== '') {
            $response->withStatus($code, $reasonPhrase);
        }
        return $response;
    }
}