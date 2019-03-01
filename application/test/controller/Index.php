<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 23:40
 */

namespace app\test\controller;


use think\Controller;

class Index extends Controller
{
    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        /*测试数据库*/
        $ret = \think\Db::name('test')->find();
        dump($ret);

        /*测试配置文件*/
        dump(\think\Config::get()['im_appkey']);

        /*测试网易云信api*/
        $IMApiModel = new \app\api\model\IMApi();
        $info = $IMApiModel->getUinfoss(['10001']);
        dump($info);

        /*测试redis服务*/
        $redis = new \think\cache\driver\Redis();
        dump($redis->handler->ping());

        \SeasLog::info('test');
    }
}