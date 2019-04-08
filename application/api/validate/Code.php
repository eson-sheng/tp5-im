<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-28
 * Time: 09:43
 */

namespace app\api\validate;

use app\api\lib\ResponseCode;
use app\api\lib\ResponseTools;
use app\api\lib\SessionTools;
use app\api\model\IMApi;
use think\Validate;

/**
 * 检查验证码类
 * Class Code
 * @package app\api\validate
 */
class Code extends Validate
{
    /**
     * 检查发送消息验证码逻辑
     * @param $param
     * @param $img_code
     * @param string $type
     * @return \think\response\Json
     */
    public function send_code ($param, $img_code, $type = '')
    {
        $session = &SessionTools::get('api');

        /*图形验证码,电话号码必传*/
        if (!$img_code && !$param) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        if ($type == 'tel') {
            /*判断手机号是否正确*/
            if ($this->checkout_tel($param)) {
                return ResponseTools::return_error(ResponseCode::TELEPHONE_ERROR);
            }
        }

        if ($type == 'email') {
            /*判断手机号是否正确*/
            if ($this->checkout_email($param)) {
                return ResponseTools::return_error(ResponseCode::EMAIL_ERROR);
            }
        }


        /*检查是否请求图形验证*/
        if (empty($session['check_img_code_time'])) {
            return ResponseTools::return_error(ResponseCode::IMG_CODE_NOT_REQ);
        }

        /*图形验证码是否过期*/
        $out_time = time() + 300;
        if ($session['check_img_code_time'] > $out_time) {
            return ResponseTools::return_error(ResponseCode::IMG_CODE_EXPIRED);
        }

        /*图形验证码是否请求*/
        if (empty($session['img_code'])) {
            return ResponseTools::return_error(ResponseCode::IMG_CODE_NOT_REQ);
        }

        /*判断图形验证码是否正确*/
        if (strtolower($img_code) != strtolower($session['img_code'])) {
            return ResponseTools::return_error(ResponseCode::IMG_CODE_ERROR);
        }

        $code_model = new \app\api\model\Code();

        /*判断验证码是否重复*/
        if ($type == 'tel') {

            /*通用方式发送短信*/
//            if ($code_model->send_code($param, 'mobile')) {
//                unset($session['check_img_code_time']);
//                return ResponseTools::return_error(ResponseCode::SUCCESS, [
//                    'type' => 1,
//                    'tip' => '验证码已经发送',
//                ]);
//            }

            /*网易云信接口发送短信*/
            $IMApi_res = $code_model->yx_send_code($param);
            return ResponseTools::return_error(ResponseCode::SUCCESS, [
                'type' => 1,
                'tip' => '验证码已经发送！',
                'IMApi_res' => $IMApi_res,
            ]);
        }

        /*判断验证码是否重复*/
        if ($type == 'email') {
            if ($code_model->send_code($param, 'email')) {
                unset($session['check_img_code_time']);
                return ResponseTools::return_error(ResponseCode::SUCCESS, [
                    'type' => 1,
                    'tip' => '验证码已经发送',
                ]);
            }
        }

        return ResponseTools::return_error($code_model->errno);
    }

    /**
     * 检查是否正确的电话号码
     * @param string $tel
     * @return bool
     */
    public function checkout_tel ($tel)
    {
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57]|16[0-9])[0-9]{8}$/';
        return preg_match($pattern, $tel) ? FALSE : TRUE;
    }

    /**
     * 检查是否正确的邮箱地址
     * @param string $email
     * @return bool
     */
    public function checkout_email ($email)
    {
        $pattern = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        return preg_match($pattern, $email) ? FALSE : TRUE;
    }

    /**
     * 检查是否正确的时间
     * @param $time
     * @return bool
     */
    public function checkout_time ($time)
    {
        $pattern = '/^([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(((0[13578]|1[02])-(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)-(0[1-9]|[12][0-9]|30))|(02-(0[1-9]|[1][0-9]|2[0-8])))\s([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/';
        return preg_match($pattern, $time) ? FALSE : TRUE;
    }

    /**
     * 检查消息验证码
     * @param $param
     * @param $code
     * @param string $type
     * @return bool|\think\response\Json
     */
    public function checkout_code ($param, $code, $type = '')
    {
        $session = &SessionTools::get('api');

        /*检查过期时间是否存在*/
        if (empty($session['check_code_time']) ||
            empty($session[$type])
        ) {
            return ResponseTools::return_error(ResponseCode::CODE_NOT_SEND);
        }

        /*检查验证码是否过期*/
        $out_time = time() + 600;
        if ($session['check_code_time'] > $out_time) {
            return ResponseTools::return_error(ResponseCode::MESSAGE_CODE_EXPIRED);
        }

        /*检查是否是发送的手机号*/
        if ($param != $session[$type]) {
            return ResponseTools::return_error(ResponseCode::TELEPHONE_ERROR);
        }

        if ($code != $session['check_code']) {
            return ResponseTools::return_error(ResponseCode::MESSAGE_CODE_ERROR);
        }

        return FALSE;
    }

    /**
     * 检查消息验证码 - 使用云信方式校验
     * @param $tel
     * @param $code
     * @return bool|\think\response\Json
     */
    public function yx_checkout_code ($tel, $code)
    {
        $session = &SessionTools::get('api');

        /*检查过期时间是否存在*/
        if (empty($session['check_code_time']) ||
            empty($session['mobile'])
        ) {
            return ResponseTools::return_error(ResponseCode::CODE_NOT_SEND);
        }

        /*检查验证码是否过期*/
        $out_time = time() + 600;
        if ($session['check_code_time'] > $out_time) {
            return ResponseTools::return_error(ResponseCode::MESSAGE_CODE_EXPIRED);
        }

        /*检查是否是发送的手机号*/
        if ($tel != $session['mobile']) {
            return ResponseTools::return_error(ResponseCode::TELEPHONE_ERROR);
        }

        /*业务 - 使用云信校验短信验证码*/
        $IMApi_model = new IMApi();
        $IMApi_res = $IMApi_model->verifycode($tel, $code);
        /*日志记录*/
        $raw = file_get_contents('php://input');
        $log = json_encode($IMApi_res);
        \SeasLog::info("\nverifycode:\n{$log}\nraw:{$raw}\n", [], "IMApi_res");

        if ($IMApi_res['code'] != 200) {
            return ResponseTools::return_error(ResponseCode::MESSAGE_CODE_ERROR, [
                'IMApi_res' => $IMApi_res,
            ]);
        }

        return FALSE;
    }
}