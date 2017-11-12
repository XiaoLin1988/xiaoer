<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/11/2017
 * Time: 2:10 PM
 */

require_once('Getui/IGt.Push.php');
require_once('Getui/igetui/IGt.AppMessage.php');
require_once('Getui/igetui/IGt.APNPayload.php');
require_once('Getui/igetui/template/IGt.BaseTemplate.php');
require_once('Getui/IGt.Batch.php');

class GetuiDemo
{
    public function __construct() {

    }

    public function pushMessageToSingle($cid = '10a0fc89eb34e6a2b43517afda710632'){
        $igt = new IGeTui(GETUI_HOST, GETUI_APPKEY, GETUI_MASTERSECRET);

        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->IGtNotificationTemplateDemo();

        //定义"SingleMessage"
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId(GETUI_APPID);
        $target->set_clientId($cid);

        try {
            $rep = $igt->pushMessageToSingle($message, $target);
            var_dump($rep);
            echo ("<br><br>");

        }catch(RequestException $e){
            $requstId =e.getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target, $requstId);
            var_dump($rep);
            echo ("<br><br>");
        }
    }

    public function pushMessageToApp(){
        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY,GETUI_MASTERSECRET);
        $template = new IGtAPNTemplate();

        //iOS推送需要设置的pushInfo字段
        $apn = new IGtAPNPayload();
        $alertmsg=new DictionaryAlertMsg();
        $alertmsg->body= "title";
        //IOS8.2 支持
        $alertmsg->title= "YuDing Info";

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->add_customMsg("yudingId", "123");
        $apn->contentAvailable=1;
        $apn->category="PushCategory_YuDing";
        $template->set_apnInfo($apn);


        $message = new IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        $rep = $igt->pushAPNMessageToSingle(GETUI_APPID, '10a0fc89eb34e6a2b43517afda710632', $message);
    }

    public function IGtLinkTemplateDemo(){
        $template =  new IGtLinkTemplate();
        $template ->set_appId(GETUI_APPID);//应用appid
        $template ->set_appkey(GETUI_APPID);//应用appkey
        $template ->set_title("请输入通知标题");//通知栏标题
        $template ->set_text("请输入通知内容");//通知栏内容
        $template ->set_logo("");//通知栏logo
        $template ->set_isRing(true);//是否响铃
        $template ->set_isVibrate(true);//是否震动
        $template ->set_isClearable(true);//通知栏是否可清除
        $template ->set_url("http://www.igetui.com/");//打开连接地址
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        return $template;
    }

    public function IGtNotyPopLoadTemplateDemo(){
        $template =  new IGtNotyPopLoadTemplate();
        $template->set_appId(GETUI_APPID);                      //应用appid
        $template->set_appkey(GETUI_APPKEY);                    //应用appkey
        //通知栏
        $template->set_notyTitle("个推");                 //通知栏标题
        $template->set_notyContent("个推最新版点击下载"); //通知栏内容
        $template->set_notyIcon("");                      //通知栏logo
        $template->set_isBelled(true);                    //是否响铃
        $template->set_isVibrationed(true);               //是否震动
        $template->set_isCleared(true);                   //通知栏是否可清除
        //弹框
        $template->set_popTitle("弹框标题");              //弹框标题
        $template->set_popContent("弹框内容");            //弹框内容
        $template->set_popImage("");                      //弹框图片
        $template->set_popButton1("下载");                //左键
        $template->set_popButton2("取消");                //右键
        //下载
        $template->set_loadIcon("");                      //弹框图片
        $template->set_loadTitle("地震速报下载");
        $template->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
        $template->set_isAutoInstall(false);
        $template->set_isActived(true);

        //设置通知定时展示时间，结束时间与开始时间相差需大于6分钟，消息推送后，客户端将在指定时间差内展示消息（误差6分钟）
        $begin = "2018-02-28 15:26:22";
        $end = "2019-02-28 15:31:24";
        $template->set_duration($begin,$end);
        return $template;
    }

    public function IGtNotificationTemplateDemo(){
        $template = new IGtNotificationTemplate();
        $template->set_appId(GETUI_APPID);                                  //应用GETUI_APPID
        $template->set_appkey(GETUI_APPKEY);                                //应用GETUI_APPKEY
        $template->set_transmissionType(1);                           //透传消息类型
        $template->set_transmissionContent("测试离线");                //透传内容
        $template->set_title("通知");                                 //通知栏标题
        $template->set_text("不是小三，是小二");                       //通知栏内容
        $template->set_logo("");                                      //通知栏logo
        $template->set_logoURL("");                                   //通知栏logo链接
        $template->set_isRing(true);                                  //是否响铃
        $template->set_isVibrate(true);                               //是否震动
        $template->set_isClearable(true);                             //通知栏是否可清除

        return $template;
    }

    public function IGtTransmissionTemplateDemo(){
        $template =  new IGtTransmissionTemplate();
        $template->set_appId(GETUI_APPID);//应用appid
        $template->set_appkey(GETUI_APPKEY);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent("测试离线ddd");//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //APN简单推送
        $template = new IGtAPNTemplate();
        $apn = new IGtAPNPayload();
        $alertmsg=new SimpleAlertMsg();
        $alertmsg->alertMsg="";
        $apn->alertMsg=$alertmsg;
        //$apn->badge=2;
        //$apn->sound="";
        $apn->add_customMsg("payload","payload");
        $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);
        //$message = new IGtSingleMessage();

        //APN高级推送
        /*
        $apn = new IGtAPNPayload();
        $alertmsg=new DictionaryAlertMsg();
        $alertmsg->body="body";
        $alertmsg->actionLocKey="ActionLockey";
        $alertmsg->locKey="LocKey";
        $alertmsg->locArgs=array("locargs");
        $alertmsg->launchImage="launchimage";
        //IOS8.2 支持
        $alertmsg->title="Title";
        $alertmsg->titleLocKey="TitleLocKey";
        $alertmsg->titleLocArgs=array("TitleLocArg");

        $apn->alertMsg=$alertmsg;
        $apn->badge=7;
        $apn->sound="";
        $apn->add_customMsg("payload","payload");
        $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);
        */

        //PushApn老方式传参
        //$template = new IGtAPNTemplate();
        //$template->set_pushInfo("", 10, "", "com.gexin.ios.silence", "", "", "", "");

        return $template;
    }
}