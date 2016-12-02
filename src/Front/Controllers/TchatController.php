<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:43
 */

namespace Front\Controllers;

use Back\Controllers\ApiController;
use Core\Controllers\Controller;
use Core\Repository\MessageRepository;
use Core\Repository\UserRepository;
use Curl\Curl;

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

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(
                $_SERVER['HTTP_X_REQUESTED_WITH']
            ) == 'xmlhttprequest'
        ) {
            $this->layout = false;
        }

        $lastId   = 0;
        $orderBy  = ['created_at' => 'DESC'];
        $limite   = 15;
        $messages = $messageRepository->findAll($lastId, $orderBy, $limite);

        uasort(
            $messages,
            function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }

                return ($a->message_created_at < $b->message_created_at) ? -1 : 1;
            }
        );

        $data = [
            'tchats' => $messages,
            'user'   => $this->session->get('user'),
            'action' => $this->router->url('api_room_tchat'),
        ];

        $template = 'index.html.twig';

        if (!$this->layout) {
            $template = '_messages.html.twig';
        }

        $this->renderTwig($template, $data);
    }

    /**
     * @param array $params
     * /refresh
     *
     * @throws \Exception
     */
    public function refreshMessagesAction(array $params = [])
    {
        $lastId = $params['lastId'];

        $this->needAuthenticated();

        $user       = $this->session->get('user');
        $curlParams = ['user' => $user->id, 'date' => time(), 'lastId' => $lastId];

        $curl = new  Curl();

        foreach ($this->generateXWSSEHeaders() as $key => $header) {
            $curl->setHeader($key, $header);
        }

        $url = $this->router->url('api_room_tchat_refresh', $curlParams, true);

        $curl->get($url);
        $messages = json_decode($curl->response, true);
        $users = json_decode($this->refreshUsersAction($params, true));

        /*
        $apiController = new ApiController($this->app);
        $messages = $apiController->refreshMessagesAction($curlParams, true);
        $users = $apiController->refreshUsersAction($curlParams, true);
        */

        $data = [
            'messages' => $messages,
            'users'    => $users,
        ];


        if ($curl->http_status_code === 200) {
            echo json_encode($data);
            exit;
        }
    }

    /**
     * @param array $params
     * @param bool  $getArrayData
     *
     * @return null
     * @throws \Exception
     */
    public function refreshUsersAction(array $params = [], $getArrayData = false)
    {
        $this->needAuthenticated();

        $curl = new  Curl();

        foreach ($this->generateXWSSEHeaders() as $key => $header) {
            $curl->setHeader($key, $header);
        }

        $user = $this->session->get('user');

        $curlParams = ['user' => $user->id];

        $url = $this->router->url('api_room_users_refresh', $curlParams, true);

        $curl->get($url);

        if ($getArrayData) {
            return $curl->response;
        }

        if ($curl->http_status_code === 200) {
            echo $curl->response;
            exit;
        }
    }
}
