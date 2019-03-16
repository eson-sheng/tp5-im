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

        /*更新用户信息*/
        if (!empty($_SESSION['api']['info']['id'])){
            $uid = $_SESSION['api']['info']['id'];
            $user_obj = \app\api\model\User::get($uid);
            if ($user_obj) {
                $user_res = $user_obj->toArray();
                unset($user_res['password'],$user_res['status'],$user_res['update_time']);
                $_SESSION['api']['info'] = $user_res;
            }
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