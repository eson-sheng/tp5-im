<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 20:40
 */

namespace app\api\controller;

use think\Controller;

class User extends Controller
{
    /**
     * /api/user/login
     * 登录
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login ()
    {
        $username = $this->request->param('username', FALSE);
        $password = $this->request->param('password', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->login($username, $password);
    }

    /**
     * /api/user/logout
     * 退出登录
     * @return \think\response\Json
     */
    public function logout ()
    {
        $validate_user = new \app\api\validate\User();
        return $validate_user->logout();
    }

    /**
     * /api/user/create_for_tel
     * 手机注册接口
     * @return bool|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function create_for_tel ()
    {
        $tel = $this->request->param('tel', FALSE);
        $code = $this->request->param('code', FALSE);
        $password = $this->request->param('password', FALSE);
        $nick = $this->request->param('nick', FALSE);
        $sex = $this->request->param('sex', FALSE);
        $base64 = $this->request->param('base64', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->create_for_tel(
            $tel,
            $code,
            $password,
            $nick,
            $sex,
            $base64
        );
    }

    /**
     * /api/user/forget_for_tel
     * 忘记密码手机重置
     * @return bool|\think\response\Json
     * @throws \think\exception\DbException
     */
    public function forget_for_tel ()
    {
        $tel = $this->request->param('tel', FALSE);
        $code = $this->request->param('code', FALSE);
        $password = $this->request->param('password', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->forget_for_tel($tel, $code, $password);
    }

    /**
     * /api/user/nick_repetition
     * 昵称重复接口
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function nick_repetition ()
    {
        $nick = $this->request->param('nick', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->nick_repetition($nick);
    }

    /**
     * /api/user/info
     * 获取用户信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function info ()
    {
        $acid = $this->request->param('acid', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->info($acid);
    }

    /**
     * /api/user/update
     * 更新用户信息
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function update ()
    {
        $nick = $this->request->param('nick', FALSE);
        $sex = $this->request->param('sex', FALSE);
        $birthday = $this->request->param('birthday', FALSE);
        $sign = $this->request->param('sign', FALSE);
        $base64 = $this->request->param('base64', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->update(
            $nick,
            $sex,
            $birthday,
            $sign,
            $base64
        );
    }

    /**
     * /api/user/update_for_tel
     * 修改手机号
     * @return bool|\think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function update_for_tel ()
    {
        $tel = $this->request->param('tel', FALSE);
        $code = $this->request->param('code', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->update_for_tel_or_email($tel, $code, 'tel');
    }

    /**
     * /api/user/update_for_email
     * 修改邮箱
     * @return bool|\think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function update_for_email ()
    {
        $email = $this->request->param('email', FALSE);
        $code = $this->request->param('code', FALSE);

        $validate_user = new \app\api\validate\User();
        return $validate_user->update_for_tel_or_email($email, $code, 'email');
    }
}