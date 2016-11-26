<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:44
 */

namespace Front\Controllers;

use Core\Controllers\Controller;

/**
 * Class UserController
 *
 * @package Front\Controllers
 */
class UserController extends Controller
{

    /**
     * @param array $params
     *
     * @throws \Exception
     */
    public function loginAction(array $params = [])
    {

        $errors = [];

        if ($this->session->has('user')) {
            $homepage = $this->router->url('api_room_tchat');
            header("Location: $homepage");
        }

        if (isset($_POST['user'])) {
            $postedUser = $_POST['user'];

                $this->session->set('user', $postedUser);

            if ($this->session->has('user')) {
                $homepage = $this->router->url('api_room_tchat');
                header("Location: $homepage");
            }
        }
        $data = [
            'action' => $homepage = $this->router->url('user_login'),
            'errors' => $errors
        ];

        $this->render('login.php', $data);
    }


    /**
     * @param array $params
     *
     * @throws \Exception
     */
    public function logoutAction(array $params = [])
    {
        $this->session->close();
        $homepage = $this->router->url('user_login');

        header("Location: $homepage");
    }
}
