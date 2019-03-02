<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 20:44
 */

namespace app\api\validate;

use app\api\lib\ResponseCode;
use app\api\lib\ResponseTools;
use think\Validate;

class User extends Validate
{
    /**
     * 手机注册检查逻辑
     * @param $tel
     * @param $code
     * @param $password
     * @param $nick
     * @param $sex
     * @param $base64
     * @return bool|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function create_for_tel ($tel, $code, $password, $nick, $sex, $base64)
    {
        /*检查是否登录 - FIXME*/

        /*检查必要参数*/
        if (!$tel || !$code || !$password || !$nick || !$sex) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        /*昵称字符数限制*/
        if (strlen($nick) > 18 || strlen($nick) < 1) {
            return ResponseTools::return_error(ResponseCode::NICK_NOT);
        }

        /*检查昵称是否重复*/
        if (\app\api\model\User::get(['nick' => $nick])) {
            return ResponseTools::return_error(ResponseCode::NICK_REPETITION);
        }

        /*判断性别参数是否正确*/
        if (!in_array(intval($sex), [0, 1])) {
            return ResponseTools::return_error(ResponseCode::INCORRECT_PARAMETER);
        }

        /*检查手机号码是否合法*/
        $code_validate = new Code();
        if ($code_validate->checkout_tel($tel)) {
            return ResponseTools::return_error(ResponseCode::TELEPHONE_ERROR);
        }

        /*检查手机号码是否注册*/
        if (\app\api\model\User::get(['tel' => $tel])) {
            return ResponseTools::return_error(ResponseCode::TELEPHONE_REGISTERED);
        }

        /*检查短信验证码合法性*/
        $ret = $code_validate->checkout_tel_code($tel, $code);
        if ($ret) {
            return $ret;
        }

        /*如果没传值初始化为空字符串*/
        if (!$base64) {
            $base64 = '';
        }

        /*通过手机号码生成新账号*/
        $user_model = new \app\api\model\User();
        $data = $user_model->user_add_for_tel(
            $tel,
            $password,
            $nick,
            $sex,
            $base64
        );
        return ResponseTools::return_error($user_model->error, $data);
    }

    /**
     * 忘记密码手机重置检查逻辑
     * @param $tel
     * @param $code
     * @param $password
     * @return bool|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function forget_for_tel ($tel, $code, $password)
    {
        /*检查是否登录 - FIXME*/

        /*检查必要参数*/
        if (!$tel || !$code || !$password) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        /*检查手机号码是否合法*/
        $code_validate = new Code();
        if ($code_validate->checkout_tel($tel)) {
            return ResponseTools::return_error(ResponseCode::TELEPHONE_ERROR);
        }

        /*检查手机号码是否注册*/
        if (!\app\api\model\User::get(['tel' => $tel])) {
            return ResponseTools::return_error(ResponseCode::TELEPHONE_NOT_REGISTERED);
        }

        /*检查短信验证码合法性*/
        $ret = $code_validate->checkout_tel_code($tel, $code);
        if ($ret) {
            return $ret;
        }

        /*通过手机号修改密码*/
        $user_model = new \app\api\model\User();
        $data = $user_model->forget_for_tel($tel, $password);
        return ResponseTools::return_error($user_model->error, $data);
    }

    /**
     * 检查昵称重复
     * @param $nick
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function nick_repetition ($nick)
    {
        /*检查必要参数*/
        if (!$nick) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        /*检查昵称是否重复*/
        if (\app\api\model\User::get(['nick' => $nick])) {
            return ResponseTools::return_error(ResponseCode::NICK_REPETITION);
        }

        return ResponseTools::return_error(ResponseCode::SUCCESS);
    }
}