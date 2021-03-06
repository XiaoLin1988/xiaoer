<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/29/2017
 * Time: 12:59 PM
 */
header("Content-Type: text/html; charset=utf-8");

require_once('Getui/IGt.Push.php');
require_once('Getui/igetui/IGt.AppMessage.php');
require_once('Getui/igetui/IGt.APNPayload.php');
require_once('Getui/igetui/template/IGt.BaseTemplate.php');
require_once('Getui/IGt.Batch.php');
require_once('Getui/igetui/utils/AppConditions.php');

class Getui {
    private $_title = "Tongzhi";
    private $_message = "Message";

    public function __construct() {

    }

    public function pushMessageToApp($text = '不是小三，是小二'){
        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY,GETUI_MASTERSECRET);
        //定义透传模板，设置透传内容，和收到消息是否立即启动启用
        $template = $this->IGtNotificationTemplateDemo();
        $template->set_text($text);
        //$template = IGtLinkTemplateDemo();
        // 定义"AppMessage"类型消息对象，设置消息内容模板、发送的目标App列表、是否支持离线发送、以及离线消息有效期(单位毫秒)
        $message = new IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);

        $appIdList=array(GETUI_APPID);
        //$phoneTypeList=array('ANDROID');
        $provinceList=array('浙江');
        $tagList=array('haha');
        //用户属性
        //$age = array("0000", "0010");


        //$cdt = new AppConditions();
        // $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
        // $cdt->addCondition(AppConditions::REGION, $provinceList);
        //$cdt->addCondition(AppConditions::TAG, $tagList);
        //$cdt->addCondition("age", $age);

        $message->set_appIdList($appIdList);
        //$message->set_conditions($cdt->getCondition());

        $rep = $igt->pushMessageToApp($message,"任务组名");

        /*
        var_dump($rep);
        echo ("<br><br>");
        */
        return $rep;
    }

    function pushMessageToSingleIOS($token,$title) {

        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY,GETUI_MASTERSECRET);
        $template = new IGtAPNTemplate();
        $template->set_pushInfo("按钮名称", 1, $title, "", $title, "", "", "");

        $message = new IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        $rep = $igt->pushAPNMessageToSingle(GETUI_APPID,$token,$message);

    }

    function pushActionToSingleIOS($token, $title, $variableName, $yudingId) {

        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY,GETUI_MASTERSECRET);
        $template = new IGtAPNTemplate();

        //iOS推送需要设置的pushInfo字段
        $apn = new IGtAPNPayload();
        $alertmsg=new DictionaryAlertMsg();
        $alertmsg->body= $title;
        //IOS8.2 支持
        $alertmsg->title= "YuDing Info";

        $apn->alertMsg=$alertmsg;
        $apn->badge=1;
        $apn->add_customMsg($variableName,$yudingId);
        $apn->contentAvailable=1;
        $apn->category="PushCategory_YuDing";
        $template->set_apnInfo($apn);


        $message = new IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        $rep = $igt->pushAPNMessageToSingle(GETUI_APPID, $token, $message);
    }

    function pushMessageToSingleAndroid($cid = 'de5a288e790455eb964b1399210f439b'){
        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY, GETUI_MASTERSECRET);
        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->IGtNotyPopLoadTemplateDemo();
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
        }catch(RequestException $e) {
            $requstId =e.getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target,$requstId);
            var_dump($rep);
            echo ("<br><br>");
        }
    }

    function IGtNotyPopLoadTemplateDemo(){
        $template = new IGtNotyPopLoadTemplate();
        $template ->set_appId(GETUI_APPID); //应用appid
        $template ->set_appkey(GETUI_APPKEY); //应用appkey
        //通知栏
        $template ->set_notyTitle("个推"); //通知栏标题
        $template ->set_notyContent("个推最新版点击下载"); //通知栏内容
        $template ->set_notyIcon(""); //通知栏logo
        $template ->set_isBelled(true); //是否响铃
        $template ->set_isVibrationed(true); //是否震动
        $template ->set_isCleared(true); //通知栏是否可清除
        //弹框
        $template ->set_popTitle("弹框标题"); //弹框标题
        $template ->set_popContent("弹框内容"); //弹框内容
        $template ->set_popImage(""); //弹框图片
        $template ->set_popButton1("下载"); //左键
        $template ->set_popButton2("取消"); //右键
        //下载
        $template ->set_loadIcon(""); //弹框图片
        $template ->set_loadTitle("地震速报下载");
        $template ->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
        $template ->set_isAutoInstall(false);
        $template ->set_isActived(true);
        //设置通知定时展示时间，结束时间与开始时间相差需大于6分钟，消息推送后，客户端将在指定时间差内展示消息（误差6分钟）
        $begin = "2017-02-28 15:26:22";
        $end = "2018-02-28 15:31:24";
        $template->set_duration($begin,$end);
        return $template;
    }

    function pushActionToSingleAndroid($token, $title, $variableName, $yudingId) {
        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY, GETUI_MASTERSECRET);
        
        $template = new IGtNotyPopLoadTemplate();
        $template ->set_appId(GETUI_APPID); //应用appid
        $template ->set_appkey(GETUI_APPKEY); //应用appkey
        //通知栏
        $template ->set_notyTitle("YuDing Info"); //通知栏标题
        $template ->set_notyContent($title); //通知栏内容
        $template ->set_notyIcon(""); //通知栏logo
        $template ->set_isBelled(true); //是否响铃
        $template ->set_isVibrationed(true); //是否震动
        $template ->set_isCleared(true); //通知栏是否可清除
        //弹框
        $template ->set_popTitle("弹框标题"); //弹框标题
        $template ->set_popContent("弹框内容"); //弹框内容
        $template ->set_popImage(""); //弹框图片
        $template ->set_popButton1("下载"); //左键
        $template ->set_popButton2("取消"); //右键
        //下载
        $template ->set_loadIcon(""); //弹框图片
        $template ->set_loadTitle("地震速报下载");
        $template ->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
        $template ->set_isAutoInstall(false);
        $template ->set_isActived(true);
        //设置通知定时展示时间，结束时间与开始时间相差需大于6分钟，消息推送后，客户端将在指定时间差内展示消息（误差6分钟）
        $begin = "2017-02-28 15:26:22";
        $end = "2018-02-28 15:31:24";
        $template->set_duration($begin,$end);

        //定义"SingleMessage"
        $message = new IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId(GETUI_APPID);
        $target->set_clientId($token);

        $igt->pushMessageToSingle($message, $target);
    }

    function pushMessageToMulti($tokenList,$title,$infomation) {

        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY,GETUI_MASTERSECRET);
        $template = new IGtAPNTemplate();
        $template->set_pushInfo("按钮名称", 1, $title, "", $infomation, "", "", "");

        putenv("needDetails=true");
        $listmessage = new IGtListMessage();
        $listmessage->set_data($template);
        $contentId = $igt->getAPNContentId(GETUI_APPID, $listmessage);
        $ret = $igt->pushAPNMessageToList(GETUI_APPID, $contentId, $tokenList);
    }

    public function setTemplate($type) {
        switch ($type) {
            case 1:  //通知透传模板
                return $this->IGtNotificationTemplate();
                break;

            case 2:  //通知链接模板
                return $this->IGtLinkTemplate();
                break;

            case 3:  //透传模板
                return $this->IGtTransmissionTemplate();
                break;

            case 4:  //通知弹框下载模板
                return $this->IGtNotyPopLoadTemplate();
                break;

            default: //通知透传模板
                return $this->IGtNotificationTemplate();
        }
    }

    public function setInfo($title, $message) {
        $this->_title = $title;
        $this->_message = $message;
    }

    public function setMessage($template, $pushtype=1, $online=false, $expire=43200000, $worktype=0) {
        switch ($pushtype) {
            case 2:  //支持对多个用户进行推送，建议为50个用户
                $message = new PushMessageToList();
                break;

            case 3:  //对单个应用下的所有用户进行推送，可根据省份，标签，机型过滤推送
                $message = new pushMessageToApp();
                break;

            default: //支持对单个用户进行推送
                $message = new IGtSingleMessage();
        }

        $message->set_isOffline($online); //是否离线
        ($online == true) && $message->set_offlineExpireTime($expire);  //离线时间

        $message->set_data($template); //设置推送消息类型
        $message->set_PushNetWorkType($worktype); //设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        return $message;
    }

    public function setTarget($client_id, $alias="") {
        $target = new IGtTarget();
        $target->set_appId(GETUI_APPID);
        $target->set_clientId($client_id);

        empty($alias) || $target->set_alias($alias);

        return $target;
    }

    public function IGtNotyPopLoadTemplate(){
        $template =  new IGtNotyPopLoadTemplate();

        $template ->set_appId(GETUI_APPID);//应用GETUI_APPID
        $template ->set_appkey(GETUI_APPKEY);//应用GETUI_APPKEY
        //通知栏
        $template ->set_notyTitle($this->_title);//通知栏标题
        $template ->set_notyContent($this->_message);//通知栏内容
        $template ->set_notyIcon("");//通知栏logo
        $template ->set_isBelled(true);//是否响铃
        $template ->set_isVibrationed(true);//是否震动
        $template ->set_isCleared(true);//通知栏是否可清除
        //弹框
        $template ->set_popTitle($this->_title);//弹框标题
        $template ->set_popContent($this->_message);//弹框内容
        $template ->set_popImage("");//弹框图片
        $template ->set_popButton1("下载");//左键
        $template ->set_popButton2("取消");//右键
        //下载
        $template ->set_loadIcon("");//弹框图片
        $template ->set_loadTitle($this->_title);
        $template ->set_loadUrl("http://192.168.1.181/download/ekong_v2.0.7.apk");

        $template ->set_isAutoInstall(false);
        $template ->set_isActived(true);

        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        return $template;
    }

    /**
     * 通知打开链接功能模板
     * @return IGtLinkTemplate
     */
    public function IGtLinkTemplate(){
        $template =  new IGtLinkTemplate();
        $template->set_appId(GETUI_APPID);//应用GETUI_APPID
        $template->set_appkey(GETUI_APPKEY);//应用GETUI_APPKEY
        $template->set_title($this->_title);//通知栏标题
        $template->set_text($this->_message);//通知栏内容
        $template ->set_logo("");//通知栏logo
        $template ->set_isRing(true);//是否响铃
        $template ->set_isVibrate(true);//是否震动
        $template ->set_isClearable(true);//通知栏是否可清除
        $template ->set_url("http://www.igetui.com/");//打开连接地址
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
        //$apn = new IGtAPNPayload();
        //$apn->alertMsg = "alertMsg";
        //$apn->badge = 11;
        //$apn->actionLocKey = "启动";
        //$apn->category = "ACTIONABLE";
        //$apn->contentAvailable = 1;
        //$apn->locKey = "通知栏内容";
        //$apn->title = "通知栏标题";
        //$apn->titleLocArgs = array("titleLocArgs");
        //$apn->titleLocKey = "通知栏标题";
        //$apn->body = "body";
        //$apn->customMsg = array("payload"=>"payload");
        //$apn->launchImage = "launchImage";
        //$apn->locArgs = array("locArgs");

        //$apn->sound=("test1.wav");;
        //$template->set_apnInfo($apn);
        return $template;
    }

    /**
     * 通知透传功能模板
     * @return IGtNotificationTemplate
     */
    public function IGtNotificationTemplate(){

        $template =  new IGtNotificationTemplate();
        $template->set_appId(GETUI_APPID);//应用GETUI_APPID
        $template->set_appkey(GETUI_APPKEY);//应用GETUI_APPKEY
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent($this->_message);//透传内容
        $template->set_title($this->_title);//通知栏标题
        $template->set_text($this->_message);//通知栏内容
        //$template->set_logo("https://www.ekong366.com/static/img/index_logo.png");//通知栏logo
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
        //$apn = new IGtAPNPayload();
        //$apn->alertMsg = "alertMsg";
        //$apn->badge = 11;
        //$apn->actionLocKey = "启动";
        //$apn->category = "ACTIONABLE";
        //$apn->contentAvailable = 1;
        //$apn->locKey = "通知栏内容";
        //$apn->title = "通知栏标题";
        //$apn->titleLocArgs = array("titleLocArgs");
        //$apn->titleLocKey = "通知栏标题";
        //$apn->body = "body";
        //$apn->customMsg = array("payload"=>"payload");
        //$apn->launchImage = "launchImage";
        //$apn->locArgs = array("locArgs");

        //$apn->sound=("test1.wav");;
        //$template->set_apnInfo($apn);
        return $template;
    }

    /**
     * 透传功能模板
     * @return IGtTransmissionTemplate
     * @throws Exception
     */
    public function IGtTransmissionTemplate(){
        $template =  new IGtTransmissionTemplate();
        $template->set_appId(GETUI_APPID);//应用GETUI_APPID
        $template->set_appkey(GETUI_APPKEY);//应用GETUI_APPKEY
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent($this->_message);//透传内容

        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //APN简单推送
        //$template = new IGtAPNTemplate();
        //$apn = new IGtAPNPayload();
        //$alertmsg=new SimpleAlertMsg();
        //$alertmsg->alertMsg="";
        //$apn->alertMsg=$alertmsg;
        //$apn->badge=2;
        //$apn->sound="";
        //$apn->add_customMsg("payload","payload");
        //$apn->contentAvailable=1;
        //$apn->category="ACTIONABLE";
        //$template->set_apnInfo($apn);
        //$message = new IGtSingleMessage();

        //APN高级推送
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

        return $template;
    }

    public function IGtNotificationTemplateDemo(){
        $template =  new IGtNotificationTemplate();
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

    public function pushMessageToSingle($clientId = '10a0fc89eb34e6a2b43517afda710632'){
        $igt = new IGeTui(GETUI_HOST,GETUI_APPKEY,GETUI_MASTERSECRET);

        $template = $this->IGtNotyPopLoadTemplateDemo();
        $message = new IGtSingleMessage();
        $message->set_isOffline(true); //是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId(GETUI_APPID);
        $target->set_clientId($clientId);
        //$target->set_alias(Alias);

        try {
            $rep = $igt->pushMessageToSingle($message, $target);
            var_dump($rep);
            echo ("<br><br>");

        }catch(RequestException $e){
            $requstId =e.getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target,$requstId);
            var_dump($rep);
            echo ("<br><br>");
        }
    }

}