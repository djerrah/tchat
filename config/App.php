<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:07
 */

namespace Config;

use Core\Router\Router;

/**
 * Class App
 *
 * @package Config
 */
class App 
{

    /**
     * @var Router
     */
    private $router;

    /**
     *
     */
    public function __construct()
    {
        define('ROOT_DIR', dirname(__DIR__));

        $this->router  = new Router();
    }

    /**
     * @return mixed
     */
    public function getRooter()
    {
        return $this->router;

    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->router->run($this);
    }
}
