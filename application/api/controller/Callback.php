<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-04-03
 * Time: 15:10
 */

namespace app\api\controller;

use think\Controller;

class Callback extends Controller
{
    private $Nonce;//随机数（最大长度128个字符）
    private $CurTime;//当前UTC时间戳，从1970年1月1日0点0 分0 秒开始到现在的秒数(String)
    private $CheckSum;//SHA1(AppSecret + Nonce + CurTime),三个参数拼接的字符串，进行SHA1哈希计算，转化成16进制字符(String，小写)

    public function index ()
    {
        $bool = $this->CheckSumBuilder();
        var_dump($bool);
    }

    /**
     * 请求Http Header校验
     * @return string
     */
    private function CheckSumBuilder ()
    {
        if (!empty($_SERVER['HTTP_MD5'])) {
            $this->Nonce = $_SERVER['HTTP_MD5'];
        }

        if (!empty($_SERVER['HTTP_CURTIME'])) {
            $this->CurTime = $_SERVER['HTTP_CURTIME'];
        }

        if (!empty($_SERVER['HTTP_CHECKSUM'])) {
            $this->CheckSum = $_SERVER['HTTP_CHECKSUM'];
        }

        $AppSecret = \think\Config::get()['im_appkey'];
        $join_string = $AppSecret . $this->Nonce . $this->CurTime;
        $CheckSum = sha1($join_string);

        return $this->CheckSum == $CheckSum ? true : false;
    }
}