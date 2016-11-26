<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:48
 */

namespace Core\Router;

use Config\App;
use Front\Controllers\TchatController;
use Front\Controllers\UserController;
use Front\Controllers\FrontController;

/**
 * Class Router
 *
 * @package Core\Router
 */
class Router 
{
    /**
     * @var
     */
    private $url;

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var array
     */
    private $names = [];

    /**
     *
     */
    public function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->init();
    }

    /**
     * Init
     */
    public function init()
    {
        #FrontController
        $this->get('home_page', '/', FrontController::class, 'indexAction');

        #UserController
        $this->get('user_login', '/login', UserController::class, 'loginAction');
        $this->post('user_login', '/login', UserController::class, 'loginAction');

        $this->get('user_logout', '/logout', UserController::class, 'logoutAction');

        #TchatController
        $this->get('api_room_tchat', '/tchat', TchatController::class, 'indexAction');
        $this->post('api_room_tchat', '/tchat', TchatController::class, 'indexAction');
    }

    /**
     * @param $name
     * @param $path
     * @param $controller
     * @param $action
     *
     * @return Route
     */
    public function gelete($name, $path, $controller, $action)
    {
        return $this->add($name, 'DELETE', $path, $controller, $action);
    }

    /**
     * @param $name
     * @param $path
     * @param $controller
     * @param $action
     *
     * @return Route
     */
    public function post($name, $path, $controller, $action)
    {
        return $this->add($name, 'POST', $path, $controller, $action);
    }

    /**
     * @param $name
     * @param $path
     * @param $controller
     * @param $action
     *
     * @return Route
     */
    public function get($name, $path, $controller, $action)
    {
        return $this->add($name, 'GET', $path, $controller, $action);
    }

    /**
     * @param $name
     * @param $method
     * @param $path
     * @param $controller
     * @param $action
     *
     * @return Route
     */
    private function add($name, $method, $path, $controller, $action)
    {
        $route = new Route($path, $controller, $action);

        $this->routes[strtoupper($method)][$name] = $route;

        $this->names[$name] = $this->routes[strtoupper($method)][$name];

        return $route;
    }

    /**
     * @throws \Exception
     */
    public function run(App $app)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (!$method) {
            throw new \Exception('No routes matches');
        }

        $match = false;

        if (isset($this->routes[$method])) {
            /**
             * @var Route $route
             */
            foreach ($this->routes[$method] as $route) {
                if ($route->match($this->url)) {
                    return $route->call($app);
                    $match = true;
                }
            }
        }

        if (!$match) {
            throw new \Exception(sprintf('No routes matches for %s %s', $method, $this->url));
        }
    }

    /**
     * @param       $name
     * @param array $parameters
     *
     * @return mixed
     * @throws \Exception
     */
    public function url($name, array $parameters = [])
    {
        if (!isset($this->names[$name])) {
            throw new \Exception(sprintf('No route match found with name = %s', $name));
        }

        return sprintf('/%s', $this->names[$name]->getUrl($parameters));
    }
}
