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
     * 验证登录逻辑
     * @param $username
     * @param $password
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login ($username, $password)
    {
        /*检查是否登录 - 已登录*/
        if (ResponseTools::checkout_login()) {
            return ResponseTools::return_error(ResponseCode::USER_IS_LOGIN);
        }

        /*参数必传*/
        if (!$username || !$password) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        /*模块查找用户*/
        $user_model = new \app\api\model\User();
        $data = $user_model->login($username, $password);
        return ResponseTools::return_error($user_model->error, $data);
    }

    /**
     * 验证退出登录
     * @return \think\response\Json
     */
    public function logout ()
    {
        /*检查是否登录 - 未登录*/
        if (!ResponseTools::checkout_login()) {
            return ResponseTools::return_error(ResponseCode::NOT_LOGIN);
        }

        $user_model = new \app\api\model\User();
        $data = $user_model->logout();
        return ResponseTools::return_error($user_model->error, $data);
    }

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
        /*检查是否登录 - 已登录*/
        if (ResponseTools::checkout_login()) {
            return ResponseTools::return_error(ResponseCode::USER_IS_LOGIN);
        }

        /*检查必要参数*/
        if (!$tel || !$code || !$password || !$nick) {
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
        if (!in_array(intval($sex), [0, 1, 2])) {
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
//        $ret = $code_validate->checkout_code($tel, $code, 'mobile');
        $ret = $code_validate->yx_checkout_code($tel, $code);
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
        /*检查是否登录 - 已登录*/
//        if (ResponseTools::checkout_login()) {
//            return ResponseTools::return_error(ResponseCode::USER_IS_LOGIN);
//        }

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
//        $ret = $code_validate->checkout_code($tel, $code, 'mobile');
        $ret = $code_validate->yx_checkout_code($tel, $code);
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

    /**
     * 检查用户信息查询方式
     * @param $acid
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function info ($acid)
    {
        /*检查是否登录 - 未登录*/
        if (!ResponseTools::checkout_login()) {
            return ResponseTools::return_error(ResponseCode::NOT_LOGIN);
        }

        /*检查必要参数*/
        if (!$acid) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        /*通过acid查看用户信息*/
        $user_model = new \app\api\model\User();
        $data = $user_model->info($acid);
        return ResponseTools::return_error($user_model->error, $data);
    }

    /**
     * 检查用户更新信息逻辑
     * @param $nick
     * @param $sex
     * @param $birthday
     * @param $sign
     * @param $base64
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function update ($nick, $sex, $birthday, $sign, $base64)
    {
        /*检查是否登录 - 未登录*/
        if (!ResponseTools::checkout_login()) {
            return ResponseTools::return_error(ResponseCode::NOT_LOGIN);
        }

        /*昵称检查*/
        if ($nick) {
            /*昵称字符数限制*/
            if (strlen($nick) > 18 || strlen($nick) < 1) {
                return ResponseTools::return_error(ResponseCode::NICK_NOT);
            }

            /*检查昵称是否重复*/
            if (\app\api\model\User::get(['nick' => $nick])) {
                return ResponseTools::return_error(ResponseCode::NICK_REPETITION);
            }
        }

        /*性别检查*/
        if ($sex) {
            /*判断性别参数是否正确*/
            if (!in_array(intval($sex), [0, 1, 2])) {
                return ResponseTools::return_error(ResponseCode::INCORRECT_PARAMETER);
            }
        }

        /*生日时间检查格式是否正确*/
        if ($birthday) {
            /*检查手机号码是否合法*/
            $code_validate = new Code();
            if ($code_validate->checkout_time($birthday)) {
                return ResponseTools::return_error(ResponseCode::ERROR_IN_TIME_FORMAT);
            }
        }

        /*检查个性签名*/
        if ($sign) {
            /*字符数限制*/
            if (strlen($sign) > 256 || strlen($sign) < 1) {
                return ResponseTools::return_error(ResponseCode::NICK_NOT);
            }
        }

        /*不传base64初始为空字符*/
        if (!$base64) {
            $base64 = '';
        }

        /*更新用户信息*/
        $user_model = new \app\api\model\User();
        $data = $user_model->update_info(
            $nick,
            $sex,
            $birthday,
            $sign,
            $base64
        );
        return ResponseTools::return_error($user_model->error, $data);
    }

    /**
     * 验证修改手机号或邮箱
     * @param $param
     * @param $code
     * @param string $type
     * @return bool|\think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function update_for_tel_or_email ($param, $code, $type = '')
    {
        /*检查是否登录 - 未登录*/
        if (!ResponseTools::checkout_login()) {
            return ResponseTools::return_error(ResponseCode::NOT_LOGIN);
        }

        /*检查必要参数*/
        if (!$param || !$code) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        $user_model = new \app\api\model\User();

        /*电话号码修改验证逻辑*/
        if ($type == 'tel') {
            /*检查手机号码是否合法*/
            $code_validate = new Code();
            if ($code_validate->checkout_tel($param)) {
                return ResponseTools::return_error(ResponseCode::TELEPHONE_ERROR);
            }

            /*检查手机号码是否注册*/
            if (\app\api\model\User::get(['tel' => $param])) {
                return ResponseTools::return_error(ResponseCode::TELEPHONE_REGISTERED);
            }

            /*检查短信验证码合法性*/
//            $ret = $code_validate->checkout_code($param, $code, 'mobile');
            $ret = $code_validate->yx_checkout_code($param, $code);
            if ($ret) {
                return $ret;
            }

            /*修改手机号码或邮箱*/
            $data = $user_model->update_for_tel_or_email($param, 'tel');
        }

        /*邮箱修改验证逻辑*/
        if ($type == 'email') {
            /*检查邮箱是否合法*/
            $code_validate = new Code();
            if ($code_validate->checkout_email($param)) {
                return ResponseTools::return_error(ResponseCode::EMAIL_ERROR);
            }

            /*检查手机号码是否注册*/
            if (\app\api\model\User::get(['email' => $param])) {
                return ResponseTools::return_error(ResponseCode::EMAIL_REGISTERED);
            }

            /*检查短信验证码合法性*/
            $ret = $code_validate->checkout_code($param, $code, 'email');
            if ($ret) {
                return $ret;
            }

            /*修改手机号码或邮箱*/
            $data = $user_model->update_for_tel_or_email($param, 'email');
        }

        return ResponseTools::return_error($user_model->error, $data);
    }

    /**
     * 搜索用户检查逻辑
     * @param $search
     * @return \think\response\Json
     */
    public function search ($search)
    {
        /*检查是否登录 - 未登录*/
        if (!ResponseTools::checkout_login()) {
            return ResponseTools::return_error(ResponseCode::NOT_LOGIN);
        }

        /*参数必传*/
        if (!$search) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        /*模块查找用户*/
        $user_model = new \app\api\model\User();
        $data = $user_model->search($search);
        return ResponseTools::return_error($user_model->error, $data);
    }
}