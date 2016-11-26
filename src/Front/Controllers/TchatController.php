<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:43
 */

namespace Front\Controllers;

use Core\Controllers\Controller;

/**
 * Class TchatController
 *
 * @package Front\Controllers
 */
class TchatController extends Controller
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
            'tchats' =>[

            ],
            'action' => $this->router->url('api_room_tchat'),
        ];

        $this->render('index.php', $data);
    }
}
