<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:41
 */

namespace Back\Controllers;

use Core\Controllers\Controller;
use Core\Repository\MessageRepository;
use Core\Repository\UserRepository;


/**
 * Class ApiController
 *
 * @package Back\Controllers
 */
class ApiController extends BaseController
{
    /**
     * @param array $params
     *
     * @return bool
     * @throws \Exception
     */
    public function refreshAction(array $params = [])
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


        $criteria['id'] = $params['user'];
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

        $messages = [];

        $lastId=$params['lastId'];
        $orderBy=['created_at', 'ASC'];
        $limite = 15;
        $messages = $messageRepository->findAll($lastId, $orderBy, $limite);

        foreach ($messageRepository->findAll($lastId) as $message) {
            unset($message->password);
            unset($message->title);
            unset($message->last_login);
            unset($message->id);
            $messages[] = $message;
        }

        uasort(
            $messages,
            function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }

                return ($a->message_created_at < $b->message_created_at) ? -1 : 1;
            }
        );

        echo json_encode($messages);
        exit;
    }

}
