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
}