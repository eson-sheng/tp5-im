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
    // 邮箱地址错误
    const EMAIL_ERROR = -1021;
    // 短信验证码错误
    const MESSAGE_CODE_ERROR = -1004;
    // 电话号码已经注册或存在
    const TELEPHONE_REGISTERED = -1005;
    // 邮箱地址已经注册或存在
    const EMAIL_REGISTERED = -1014;
    // 短信验证已经发送,请勿重复发送
    const MESSAGE_CODE_IS_SEND = -1006;
    // 短信验证码尚未发送
    const MESSAGE_CODE_NOT_SEND = -1007;
    // 短信验证码已过期
    const MESSAGE_CODE_EXPIRED = -1008;
    // 验证码尚未发送
    const CODE_NOT_SEND = -1204;
    // 昵称不符合规范，字符大于1小于6
    const NICK_NOT = -1009;
    // 图片验证码码错误
    const IMG_CODE_ERROR = -1010;
    // 昵称重复
    const NICK_REPETITION = -1011;
    // 头像格式错误，无法存储。
    const PIC_ERROR = -1012;
    // 手机号码未注册
    const TELEPHONE_NOT_REGISTERED = -1013;
    // 图形验证码尚未请求
    const IMG_CODE_NOT_REQ = -1019;
    // 图形验证码过期
    const IMG_CODE_EXPIRED = -1020;
    // 获取数据出错
    const GET_DATA_FAILED = -1203;
    // 参数不正确
    const INCORRECT_PARAMETER = -1108;
    // 数据保存重复或失败
    const DATA_DUPLICATION = -1111;
    // 用户已经登录
    const USER_IS_LOGIN = -1112;
    // 账号信息不存在
    const NOT_HAVE_USERNAME = -1041;
    // 用户名或密码不正确
    const PASSWORD_AUTHENTICATION_FAILED = -1402;
    // 用户未登录
    const NOT_LOGIN = -1403;
    // 时间格式错误
    const ERROR_IN_TIME_FORMAT = -1022;

    const CODE_MAP = [
        self::SUCCESS => 'OKAY',
        self::PARAMETER_INCOMPLETENESS => '参数不完整',
        self::NOT_HAVE_TELEPHONE => '没有电话号码',
        self::NOT_HAVE_MESSAGE_CODE => '没有短信验证码',
        self::TELEPHONE_ERROR => '电话号码错误',
        self::MESSAGE_CODE_ERROR => '短信验证码错误',
        self::TELEPHONE_REGISTERED => '电话号码已经注册或存在',
        self::EMAIL_REGISTERED => '邮箱地址已经注册或存在',
        self::MESSAGE_CODE_IS_SEND => '短信验证已经发送,请勿重复发送',
        self::MESSAGE_CODE_NOT_SEND => '短信验证码尚未发送',
        self::MESSAGE_CODE_EXPIRED => '短信验证码已过期',
        self::IMG_CODE_ERROR => '图片验证码码错误',
        self::IMG_CODE_NOT_REQ => '图形验证码尚未请求',
        self::IMG_CODE_EXPIRED => '图形验证码过期',
        self::GET_DATA_FAILED => '获取数据出错',
        self::NICK_NOT => '昵称不符合规范，字符大于1小于6',
        self::NICK_REPETITION => '昵称重复',
        self::PIC_ERROR => '头像格式错误，无法存储。',
        self::INCORRECT_PARAMETER => '参数不正确',
        self::TELEPHONE_NOT_REGISTERED => '手机号码未注册',
        self::DATA_DUPLICATION => '数据保存重复或失败',
        self::USER_IS_LOGIN => '用户已经登录',
        self::NOT_HAVE_USERNAME => '账号信息不存在',
        self::PASSWORD_AUTHENTICATION_FAILED => '用户名或密码不正确',
        self::NOT_LOGIN => '用户未登录',
        self::ERROR_IN_TIME_FORMAT => '时间格式错误',
        self::EMAIL_ERROR => '邮箱地址错误',
        self::CODE_NOT_SEND => '验证码尚未发送',
    ];
}