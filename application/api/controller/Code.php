<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 20:41
 */

namespace app\api\controller;

use app\api\lib\SessionTools;
use think\Controller;

/**
 * 验证码接口类
 * Class Code
 * @package app\api\controller
 */
class Code extends Controller
{
    /**
     * /api/code/img
     * 图形验证接口
     */
    public function img()
    {
        $VerifyCode = new \app\api\lib\VerifyCode();
        $code_str = $VerifyCode->get_code();
        $session = &SessionTools::get('api');
        $session['img_code'] = $code_str;
        $session['check_img_code_time'] = time();

        // 设置接口输出数据
        $obj = new \app\api\lib\VerifyImage();
        $obj->createImage();
    }

    /**
     * /api/code/tel
     * 发送手机验证码
     * @return \think\response\Json
     */
    public function tel ()
    {
        $tel = $this->request->param('tel', FALSE);
        $img_code = $this->request->param('img_code', FALSE);

        $validate_code = new \app\api\validate\Code();
        return $validate_code->code_tel($tel, $img_code);
    }
}