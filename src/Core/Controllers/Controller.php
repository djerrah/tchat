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
     * @var string
     */
    protected $apiUser = 'api_user';

    /**
     * @var string
     */
    protected $apiPassword = 'api_password';


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
        $this->app     = $app;
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
     * @param       $filename
     * @param array $data
     */
    public function renderTwig($filename, array $data = [])
    {

        $data = array_merge(
            $data,
            [
                'router' => $router = $this->app->getRooter()
            ]
        );

        $calledClassFile = $this->urlConstructor(array_merge([ROOT_DIR, 'src'], explode("\\", get_called_class())));
        $viewFolder      = $this->urlConstructor([dirname(dirname($calledClassFile)), 'Views']);

        $paths = [
            $viewFolder,
            $this->urlConstructor([$viewFolder, $this->getClassName()])
        ];

        $loader = new \Twig_Loader_Filesystem($paths);
        $twig   = new \Twig_Environment(
            $loader, [
                'cache' => ROOT_DIR . '/cache/twig',
            ]
        );

        echo $twig->render($this->urlConstructor([$this->getClassName(), $filename]), $data);
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

    /**
     * @return array
     */
    protected function generateXWSSEHeaders()
    {
        $apiToken = '7d389e70-6d4a-4242-81b9-ab926e8d64b7';
        $apiUser  = $this->apiUser;
        $nonce    = uniqid();
        $created  = date('c');
        $digest   = base64_encode(sha1(base64_decode($nonce) . $created . $this->apiPassword, true));

        $wsseAuthorization = "WSSE profile=\"$apiToken\"\n";
        $wsseHeader        = sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $apiUser,
            $digest,
            $nonce,
            $created
        );

        return [
            'X-WSSE'        => $wsseHeader,
            'Authorization' => $wsseAuthorization,
        ];
    }
}
