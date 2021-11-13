<?php declare(strict_types=1);

namespace Backend;

class Router
{
    /** @var Route[] */
    private array $routes;

    /**
     * @param array $routes
     * @return Router
     */

    //route objects instanziieren und in Eigenschaft von router speichern
    public function append(array $routes): Router
    {
        $this->routes = array_map(static function (array $route) {
             return new Route($route['type'], $route['path'], $route['class']);
        }, $routes);
        return $this;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}