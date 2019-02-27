<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 22:13
 */

namespace app\api\lib;


class SessionTools
{
    public static function init()
    {

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