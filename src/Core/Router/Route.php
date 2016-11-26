<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:48
 */

namespace Core\Router;

use Config\App;

/**
 * Class Route
 *
 * @package Core\Router
 */
class Route
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var
     */
    private $controller;

    /**
     * @var
     */
    private $action;

    /**
     * @var array
     */
    private $matches = [];

    /**
     * @var array
     */
    private $params = [];

    /**
     * @param $path
     * @param $controller
     * @param $action
     */
    public function __construct($path, $controller, $action)
    {
        $this->path       = trim($path, '/');
        $this->controller = $controller;
        $this->action     = $action;
    }


    /**
     * @param $url
     *
     * @return bool
     */
    public function match($url)
    {

        $url  = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);


        if (count($this->params)) {
            $paramNames = array_keys($this->params);
        } else {
            preg_match('#:([\w]+)#', $this->path, $paramNames);
            array_shift($paramNames);
        }

        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);

        $this->matches = array_combine($paramNames, $matches);

        return true;
    }

    /**
     * @param $match
     *
     * @return string
     */
    private function paramMatch($match)
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }

        return '([^/]+)';
    }

    /**
     *
     */
    public function call(App $app)
    {
        $controller = new $this->controller($app);

        if (method_exists($controller, $this->action)) {
            return $controller->{$this->action}($this->matches);
        }

    }

    /**
     * @param $param
     * @param $regex
     *
     * @return $this
     */
    public function with($param, $regex)
    {
        $this->params[$param] = $regex;

        return $this;
    }

    /**
     * @param $params
     *
     * @return mixed|string
     */
    public function getUrl($params)
    {
        $path      = $this->path;
        $urlParams = "?";
        foreach ($params as $key => $param) {
            if (preg_match(":$key", $path)) {
                $path = str_replace(":$key", $param, $path);
                unset($params[$key]);
            } else {
                $urlParams .= "$key=$param";
            }
        }

        if (count($params)) {
            $path .= $urlParams;
        }

        return $path;
    }
}
