<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 13:24
 */

namespace Core\Session;

/**
 * Class Session
 *
 * @package Core\Session
 */
class Session 
{
    /**
     * Get Session
     */
    public function start()
    {

        session_start();
    }
    /**
     * Get Session
     */
    public function close()
    {
        session_destroy();
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);

    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function get($key)
    {
        return (isset($_SESSION[$key]))? $_SESSION[$key] : false;
    }
}
