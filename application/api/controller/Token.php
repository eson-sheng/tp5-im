<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-03-21
 * Time: 10:47
 */

namespace app\api\controller;

use app\api\lib\ResponseCode;
use app\api\lib\ResponseTools;
use think\Controller;

/**
 * 令牌更新类
 * Class Token
 * @package app\api\controller
 */
class Token extends Controller
{
    /**
     * /api/token/index
     * 设置cookie中PHP_SESSION_ID参数接口
     * @return \think\response\Json
     */
    public function index ()
    {
        $token = $this->request->param('token', FALSE);
        setcookie("PHPSESSID", $token, 0, '/');

        return ResponseTools::return_error(ResponseCode::SUCCESS, []);
    }
}