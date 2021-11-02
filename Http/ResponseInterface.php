<?php declare(strict_types=1);

namespace Http;

use JsonException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ResponseInterface
{
    /** @var HttpResponse */
    private $response;

    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @throws JsonException
     */
    public function withJson(): HttpResponse
    {
        $this->body = json_encode($this->response->getBody(), JSON_THROW_ON_ERROR);
        return $this->response;
    }

    public function render(HttpResponse $response, $file) {
        $loader = new FilesystemLoader(__DIR__.'/../templates');
        $twig = new Environment($loader, [
            'cache' => '/path/to/compilation_cache',
        ]);

        $template = $twig->load('homepage.twig');

    }


}