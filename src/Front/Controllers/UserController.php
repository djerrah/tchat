<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:44
 */

namespace Front\Controllers;

use Core\Controllers\Controller;
use Core\Repository\UserRepository;

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
        $data =  [];

        $userRepository = $this->app->getRepository('user');

        if ($this->session->has('user')) {
            $homepage = $this->router->url('home_page');
            header("Location: $homepage");
        }

        if (isset($_POST['user'])) {
            $isValide   = false;
            $postedUser = $_POST['user'];

            $postedUser['password'] = sha1($postedUser['password']);

            $bddUser = $userRepository->findOneByCriteria(['username' => $postedUser['username']]);

            if ($bddUser) {
                if ($bddUser->password === $postedUser['password']) {
                    $isValide = true;
                    unset($postedUser['password']);
                    unset($postedUser['username']);
                    $userRepository->update($postedUser, ['id' => $bddUser->id]);
                }else{
                    $data['error'] = 'Invalid password';
                    $data['loginUser'] = $postedUser;
                    goto fin_de_scripte;
                }
            } else {
                $bddUser  = $userRepository->insert($postedUser);
                $isValide = true;
            }

            if ($isValide) {
                $bddUser->last_login = new \DateTime($bddUser->last_login);
                $this->session->set('user', $bddUser);
            }
        }

        if ($this->session->has('user')) {
            $homepage = $this->router->url('api_room_tchat');
            header("Location: $homepage");
        }

        fin_de_scripte:

        $data['action'] = $homepage = $this->router->url('user_login');
        $this->renderTwig('login.html.twig', $data);
    }


    /**
     * @param array $params
     *
     * @throws \Exception
     */
    public function logoutAction(array $params = [])
    {
        $homepage = $this->router->url('user_login');

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->app->getRepository('user');

        $criteria['id'] = $this->app->getSession()->get('user')->id;
        $data['online'] = 0;
        $userRepository->update($data, $criteria);
        $this->session->close();

        header("Location: $homepage");
    }
}
