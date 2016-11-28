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

        $curl = new  Curl();

        foreach($this->generateXWSSEHeaders() as $key => $header) {
            $curl->setHeader($key, $header);
        }

        $user = $this->session->get('user');

        $curlParams = ['user'=>$user->id, 'date'=>time()];

        $url = $this->router->url('api_room_tchat_refresh', $curlParams, true);

        $curl->get($url);
        $response = $curl->response;

        var_dump(json_decode($response,true));
    }

}
