<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-04-04
 * Time: 14:03
 */

namespace app\api\model;
require_once __DIR__ . "/GETUI_PHP_SDK_4.1.0.0/IGt.Push.php";

use app\api\lib\SessionTools;

class IGtApi
{
    private $AppID;
    private $AppSecret;
    private $Appkey;
    private $MasterSecret;
    private $host = 'http://sdk.open.api.igexin.com/apiex.htm';

    public function __construct ()
    {
        $this->AppID = \think\Config::get()['igt_appid'];
        $this->AppSecret = \think\Config::get()['igt_appsecret'];
        $this->Appkey = \think\Config::get()['igt_appkey'];
        $this->MasterSecret = \think\Config::get()['igt_mastersecret'];
    }

    public function aliasBind ($cid)
    {
        $igt = new \IGeTui(
            $this->host,
            $this->Appkey,
            $this->MasterSecret
        );

        $session = &SessionTools::get('api');
        $ret = $igt->bindAlias(
            $this->AppID,
            $session['info']['acid'],
            $cid
        );
        $raw = file_get_contents('php://input');
        $info = print_r($ret,1);
        \SeasLog::info("\nbindAlias:\n{$info}\nraw:{$raw}\n", [], "IGtApi_res");

        return $ret;
    }

    public function pushMessageToSingle ($alias)
    {
        $igt = new \IGeTui(
            $this->host,
            $this->Appkey,
            $this->MasterSecret
        );

        //消息模版：
        $template = $this->IGtNotificationTemplate();

        $message = new \IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);

        //接收方
        $target = new \IGtTarget();
        $target->set_appId($this->AppID);
        $target->set_alias($alias);

        $ret = $igt->pushMessageToSingle($message, $target);
        $raw = file_get_contents('php://input');
        $info = print_r($ret,1);
        \SeasLog::info("\npushMessageToSingle:{$alias}\n{$info}\nraw:{$raw}\n", [], "IGtApi_res");
        return $ret;
    }

    private function IGtNotificationTemplate ()
    {
        $template = new \IGtNotificationTemplate();
        $template->set_appId($this->AppID);//应用appid
        $template->set_appkey($this->Appkey);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent(" ");//透传内容
        $template->set_title("通知新消息");//通知栏标题
        $template->set_text("您有一条新消息，请注意查看！");//通知栏内容
        $template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        $apn = new \IGtAPNPayload();
        $alertmsg = new \SimpleAlertMsg();
        $alertmsg->alertMsg = "您有一条新消息，请注意查看！";
        $apn->alertMsg = $alertmsg;
        $apn->badge = 0;
        $apn->sound = "";
        $apn->add_customMsg("payload", "payload");
        $apn->contentAvailable = 1;
        $apn->category = "ACTIONABLE";
        $template->set_apnInfo($apn);

        return $template;
    }

}