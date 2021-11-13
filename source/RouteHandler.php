<?php declare(strict_types=1);
//nimmt router und macht request vergleiche, sowie führt routes klassen aus
namespace Backend;


use Backend\Http\ServerRequest;
use Http\HttpRequest;
use Http\HttpResponse;
use LogicException;
use RuntimeException;

class RouteHandler
{

    /** @var Router */
    private Router $router;

    /** @var Route[] */
    private array $routes;

    /** @var Route */
    private Route $matchedRoute;

    /**
     * @param Router $router
     * @param array $routes
     */
    //router object
    public function __construct(Router $router, array $routes)
    {
        $this->router = $router;
        $router->append($routes);

    }

    /**
     * @return void
     */

    ///findet route object welches zum request path passt
    /*public function findRoute(): void
    {
        foreach ($this->router->getRoutes() as $route) {
            if ($route->getPath() === HttpRequest::getPath()) {
                if ($route->getType() !== HttpRequest::getMethod()) {
                    throw new LogicException('Route type mismatch.');
                }
                $this->matchedRoute = $route;
            }
        }
    }*/

    public function findRoute(array $routes, ServerRequest $request)
    {
        foreach ($this->router->getRoutes() as $route) {
            if ($route->getPath() === $request->getUri()->getPath()) {
                if ($route->getType() !== $request->getMethod()) {
                    throw new LogicException('Route type mismatch.');
                }
                $this->matchedRoute = $route;
            }
        }

    }

    //führt class inhalt aus

    /**
     * @return void
     */
    public function runClassOfRoute(Route $matchedRoute): array
    {
        $class = strtok($matchedRoute->getClass(), ':');
        $methodOfClass = substr($matchedRoute->getClass(),
            strpos($matchedRoute->getClass(), ':')+1);

        if (!$class) {
            throw new RuntimeException('could not read class');
        }

        if (!$methodOfClass) {
            throw new RuntimeException('could not read method');
        }

        $object = new $class;

        return $object->$methodOfClass();

    }

    /**
     * @return Route
     */
    public function getMatchedRoute(): Route
    {
        return $this->matchedRoute;
    }
}