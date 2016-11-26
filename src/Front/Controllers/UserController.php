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

        $data  =[];

        $this->render('login.php', $data);
    }


    /**
     * @param array $params
     *
     * @throws \Exception
     */
    public function logoutAction(array $params = [])
    {
        $homepage = $this->router->url('user_login');

        header("Location: $homepage");
    }
}
