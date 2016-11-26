<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:07
 */

namespace Config;

use Core\Router\Router;
use Core\Session\Session;

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
     * @var Session
     */
    private $session;

    /**
     *
     */
    public function __construct()
    {
        define('ROOT_DIR', dirname(__DIR__));

        $this->router  = new Router();
        $this->session = new Session();
        $this->session->start();

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

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

}
