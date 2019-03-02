<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 20:42
 */

namespace app\api\model;

use app\api\lib\ResponseCode;
use app\api\lib\SessionTools;
use think\Model;

class User extends Model
{
    public $error = NULL;

    /**
     * 用户密码加密处理
     * @param string $password 密码
     * @return string
     */
    private function _password_generate ($password)
    {
        $mi = \think\Config::get()['im_appkey'];
        $pwd = md5("salt-{$mi}-{$password}");
        return $pwd;
    }

    /**
     * 存储base64为头像文件
     * @param $base64
     * @return bool|string
     */
    private function _pic ($base64)
    {
        if (strstr($base64, ",")) {
            $base64 = explode(',', $base64)[1];
        }

        $base64_decoded = base64_decode($base64, TRUE);

        $upload_pic = __DIR__ . "/../../../public/upload_pic";
        if (!file_exists($upload_pic)) {
            mkdir($upload_pic, 0777);
        }

        $file_name = md5(time());

        if ($base64_decoded) {
            file_put_contents("{$upload_pic}/{$file_name}", $base64_decoded);
            return "/upload_pic/{$file_name}";
        }

        return FALSE;
    }

    /**
     * 手机创建账号
     * @param $tel
     * @param $password
     * @param $nick
     * @param $sex
     * @param $base64
     * @return array
     */
    public function user_add_for_tel ($tel, $password, $nick, $sex, $base64)
    {
        /*头像处理错误返回*/
        $pic = $this->_pic($base64);
        if (!empty($base64) && !$pic) {
            $this->error = ResponseCode::PIC_ERROR;
            return [];
        }

        $param = [
            'acid' => NumId::generateNumber($tel),
            'password' => $this->_password_generate($password),
            'tel' => trim($tel),
            'nick' => trim($nick),
            'sex' => $sex,
            'pic' => $pic,
        ];

        $user = new User();
        if ($user->save($param)) {
            /*返回错误码*/
            $this->error = ResponseCode::SUCCESS;
        } else {
            $this->error = ResponseCode::GET_DATA_FAILED;
            return [];
        }

        $request = \think\Request::instance();
        $domain = $request->domain();
        !empty($pic) ? $domain_pic_url = "{$domain}{$user->pic}" : $domain_pic_url = '';

        $user_res = [
            'id' => $user->id,
            'acid' => $user->acid,
            'tel' => $user->tel,
            'pic' => $domain_pic_url,
            'nick' => $user->nick,
            'sex' => $user->sex,
        ];

        /*删除验证时间*/
        $session = &SessionTools::get('api');
        unset($session['check_code_time']);

        /*业务 - 创建云信ID*/
        $IMApi_model = new IMApi();
        $IMApi_res = $IMApi_model->createUserIds(
            $param['acid'],
            $param['nick'],
            json_encode($user_res),
            $user_res['pic']
        );
        /*日志记录*/
        $log = json_encode($IMApi_res);
        \SeasLog::info("\n{$log}\n", [], "IMApi_res");

        return $user_res;
    }

    /**
     * 忘记密码手机重置数据库逻辑
     * @param $tel
     * @param $password
     * @return array
     * @throws \think\exception\DbException
     */
    public function forget_for_tel ($tel, $password)
    {
        /*删除验证时间*/
        $session = &SessionTools::get('api');
        unset($session['check_code_time']);
        /*处理密码加密*/
        $mi = $this->_password_generate($password);
        /*获取数据并修改*/
        $user_obj = User::get(['tel' => $tel]);
        $user_obj->password = $mi;
        if ($user_obj->save()) {
            $this->error = ResponseCode::SUCCESS;
            return [];
        }
        $this->error = ResponseCode::DATA_DUPLICATION;
        return [];
    }
}