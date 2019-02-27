<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 20:41
 */

namespace app\api\controller;

use think\Controller;
use app\api\lib\SessionTools;

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
}