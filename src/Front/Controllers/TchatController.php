<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:43
 */

namespace Front\Controllers;

use Core\Controllers\Controller;
use Core\Repository\MessageRepository;
use Core\Repository\UserRepository;

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

        /**
         * @var MessageRepository $messageRepository
         */
        $messageRepository = $this->app->getRepository('message');

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->app->getRepository('user');


        $criteria['id'] = $this->app->getSession()->get('user')->id;
        $data['online'] = 1;
        $userRepository->update($data, $criteria);

        if (isset($_POST['tchat'])) {
            $tchat = $_POST['tchat'];
            if (isset($tchat['body']) && trim($tchat['body'])) {
                $messageRepository->insert($tchat);
            }
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->layout = false;
        }

        $messages = $messageRepository->findAll();

        uasort(
            $messages,
            function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }

                return ($a->created_at < $b->created_at) ? -1 : 1;
            }
        );

        $data = [
            'displayForm' => (($this->layout) ? true : false),
            'tchats'      => $messages,
            'user'        => $this->session->get('user'),
            'action'      => $this->router->url('api_room_tchat'),
        ];

        $this->render('index.php', $data);
    }
}
