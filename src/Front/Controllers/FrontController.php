<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:43
 */

namespace Front\Controllers;

use Core\Controllers\Controller;
use Curl\Curl;

/**
 * Class FrontController
 *
 * @package Front\Controllers
 */
class FrontController extends Controller
{

    /**
     * @param array $params
     *
     * @throws \Exception
     */
    public function indexAction(array $params = [])
    {

        $this->needAuthenticated();

        $data = [
            'user' => $this->session->get('user')
        ];

        $this->renderTwig('index.html.twig', $data);
    }

}
