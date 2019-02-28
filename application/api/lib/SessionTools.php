<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 22:13
 */

namespace app\api\lib;

/**
 * 会话处理类
 * Class SessionTools
 * @package app\api\lib
 */
class SessionTools
{
    public static function init()
    {
        if (!session_id()){
            session_start();
        }
    }

    public static function &get($key, $default = null)
    {
        self::init();
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        $_SESSION[$key] = $default;
        return $_SESSION[$key];
    }
}