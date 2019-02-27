<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 22:20
 */

namespace app\api\lib;


class VerifyCode
{
    /**
     * 获取随机数字 - 用于图形验证码
     * @param $length int
     * @return string
     */
    public function get_code ($length = 6)
    {
        $str = null;
        $strPol = "0123456789ABCDEFGHGKLMNPQRSUVWXYZabcdefghigklmnpqrsuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
}