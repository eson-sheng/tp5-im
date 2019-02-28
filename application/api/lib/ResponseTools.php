<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-28
 * Time: 00:34
 */

namespace app\api\lib;


use think\response\Json;

/**
 * 响应返回处理类
 * Class ResponseTools
 * @package app\api\lib
 */
class ResponseTools
{
    /**
     * 返回错误信息
     * @param $errno
     * @param mixed $data
     * @param bool $status
     * @return Json
     */
    public static function return_error($errno, $data = [], $status = false)
    {
        if ($errno == ResponseCode::SUCCESS) {
            $status = true;
        }

        $error_msg = ResponseCode::CODE_MAP[$errno];
        return json([
            'errno' => $errno,
            'data' => $data,
            'status' => $status,
            'error_msg' => $error_msg,
        ]);
    }
}