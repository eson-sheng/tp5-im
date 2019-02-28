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
use think\Validate;

/**
 * 检查验证码类
 * Class Code
 * @package app\api\validate
 */
class Code extends Validate
{
    public function code_tel ($tel, $img_code)
    {
        $session = &SessionTools::get('api');

        /*图形验证码,电话号码必传*/
        if (!$img_code && !$tel) {
            return ResponseTools::return_error(ResponseCode::PARAMETER_INCOMPLETENESS);
        }

        /*判断手机号是否正确*/
        if ($this->checkout_tel($tel)) {
            return ResponseTools::return_error(ResponseCode::TELEPHONE_ERROR);
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

        /*判断验证码是否重复*/
        $code_model = new \app\api\model\Code();
        if ($code_model->send_tel_code($tel)) {
            unset($session['check_img_code_time']);
            return ResponseTools::return_error(ResponseCode::SUCCESS,[
                'type'=>1,
                'tip'=>'验证码已经发送',
            ]);
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
        $pattern = '/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/';
        return preg_match($pattern, $tel) ? FALSE : TRUE;
    }
}