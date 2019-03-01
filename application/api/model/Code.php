<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-28
 * Time: 09:52
 */

namespace app\api\model;

use app\api\lib\ResponseCode;
use app\api\lib\SessionTools;

/**
 * 验证码模块
 * Class Code
 * @package app\api\model
 */
class Code
{
    public $errno = NULL;

    /**
     * @param $tel
     * @return bool
     */
    public function send_tel_code ($tel)
    {
        /*实例化redis类*/
        $redis = new \think\Cache\driver\Redis();
        /*判断tel是否存在，重复发送*/
        if ($redis->handler->get($tel)) {
            $this->errno = ResponseCode::MESSAGE_CODE_IS_SEND;
            return FALSE;
        }
        /*实例化验证码类*/
        $VerifyCode = new \app\api\lib\VerifyCode();
        /*获取验证码字符串*/
        $code = $VerifyCode->get_code_tel();
        /*存储redis缓存*/
        $redis->handler->setex($tel,60,$code);
        /*记录会话时间*/
        $session = &SessionTools::get('api');
        $session['mobile'] = $tel;
        $session['check_code'] = $code;
        $session['check_code_time'] = time();
        /*发送验证码*/
        $VerifyCode->sendTelMes($tel,$code);
        return TRUE;
    }
}