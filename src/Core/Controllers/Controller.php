<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:41
 */

namespace Core\Controllers;

use Core\Router\Router;
use Config\App;
use Core\Session\Session;

/**
 * Class Controller
 *
 * @package Core\Controllers
 */
class Controller 
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var string
     */
    protected $layout = 'default';

    /**
     * Constructor
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->router  = $app->getRooter();
        $this->session = $app->getSession();

    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return strtolower(str_replace('Controller', '', @array_pop(explode("\\", get_called_class()))));
    }


    /**
     * @param       $filename
     * @param array $data
     */
    public function render($filename, array $data = [])
    {

        $calledClassFile = $this->urlConstructor(array_merge([ROOT_DIR, 'src'], explode("\\", get_called_class())));
        $viewFolder      = $this->urlConstructor([dirname(dirname($calledClassFile)), 'Views']);

        extract($data);
        $router = $this->app->getRooter();

        ob_start();
        require($this->urlConstructor([$viewFolder, $this->getClassName(), $filename]));
        $content = ob_get_clean();

        if (!$this->layout) {
            echo $content;
        } else {
            $pathPArts = [dirname(dirname($calledClassFile)), 'Views', 'layout', $this->layout . '.php'];
            require($this->urlConstructor($pathPArts));
        }

    }

    /**
     * @param $paths
     *
     * @return string
     */
    private function urlConstructor($paths)
    {
        return implode(DIRECTORY_SEPARATOR, $paths);
    }


    /**
     * @throws \Exception
     */
    protected function needAuthenticated()
    {
        if (!$this->session->has('user')) {
            $homepage = $this->router->url('user_login');
            header("Location: $homepage");
        }

    }
}
