<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-28
 * Time: 00:40
 */

namespace app\api\lib;

/**
 * 错误码地图类
 * Class ResponseCode
 * @package app\api\lib
 */
class ResponseCode
{
    // 成功
    const SUCCESS = 200;
    // 参数不完整
    const PARAMETER_INCOMPLETENESS = -1301;
    // 没有电话号码
    const NOT_HAVE_TELEPHONE = -1001;
    // 没有短信验证码
    const NOT_HAVE_MESSAGE_CODE = -1002;
    // 电话号码错误
    const TELEPHONE_ERROR = -1003;
    // 短信验证码错误
    const MESSAGE_CODE_ERROR = -1004;
    // 电话号码已经注册
    const TELEPHONE_REGISTERED = -1005;
    // 短信验证已经发送,请勿重复发送
    const MESSAGE_CODE_IS_SEND = -1006;
    // 短信验证码尚未发送
    const MESSAGE_CODE_NOT_SEND = -1007;
    // 短信验证码已过期
    const MESSAGE_CODE_EXPIRED = -1008;
    // 图片验证码码错误
    const IMG_CODE_ERROR = -1010;
    // 图形验证码尚未请求
    const IMG_CODE_NOT_REQ = -1019;
    // 图形验证码过期
    const IMG_CODE_EXPIRED = -1020;

    const CODE_MAP = [
        self::SUCCESS => 'OKAY',
        self::PARAMETER_INCOMPLETENESS => '参数不完整',
        self::NOT_HAVE_TELEPHONE => '没有电话号码',
        self::NOT_HAVE_MESSAGE_CODE => '没有短信验证码',
        self::TELEPHONE_ERROR => '电话号码错误',
        self::MESSAGE_CODE_ERROR => '短信验证码错误',
        self::TELEPHONE_REGISTERED => '电话号码已经注册',
        self::MESSAGE_CODE_IS_SEND => '短信验证已经发送,请勿重复发送',
        self::MESSAGE_CODE_NOT_SEND => '短信验证码尚未发送',
        self::MESSAGE_CODE_EXPIRED => '短信验证码已过期',
        self::IMG_CODE_ERROR => '图片验证码码错误',
        self::IMG_CODE_NOT_REQ => '图形验证码尚未请求',
        self::IMG_CODE_EXPIRED => '图形验证码过期',
    ];
}