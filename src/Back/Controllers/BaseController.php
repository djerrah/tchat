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
 * Class BaseController
 *
 * @package Back\Controllers
 */
class BaseController extends Controller
{
    /**
     * @throws \Exception
     */
    protected function needAuthenticated()
    {
        try {

            $headers = apache_request_headers();

            $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
            if (!isset($headers["X-WSSE"]) || 1 !== preg_match($wsseRegex, $headers['X-WSSE'], $matches)) {
                $message = "The is no x-wsse request header (authentication failed).";
                throw new \Exception($message);
            }


            array_shift($matches);

            $user    = $matches[0];
            $digest  = $matches[1];
            $nonce   = $matches[2];
            $created = $matches[3];

            if ($this->apiUser != $user) {
                $message = sprintf("The user with the username <<%s>> is not found", $user);
                throw new \Exception($message);
            }

            $expected = base64_encode(sha1(base64_decode($nonce) . $created . $this->apiPassword, true));

            if ($expected != $digest) {
                $message = "invalid Password";
                throw new \Exception($message);
            }
        } catch (\Exception $e) {
            http_response_code(401);
            echo $e->getMessage();
            exit;
        }

    }
}
