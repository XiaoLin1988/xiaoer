<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/22/2017
 * Time: 4:13 PM
 */
class Qingke extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Qingke_model', 'qingke');
        $this->load->model('Mjiu_model', 'mjiu');
        $this->load->model('Yonghu_model', 'yonghu');
        $this->load->model('Jiu_model', 'jiu');
        $this->load->model('Shangjia_model', 'shangjia');

        $this->load->library('Getui', 'getui');
    }

    public function create() {
        $result = array();

        $qk_code = $this->createVerificationCode(11);

        $qingkeData = array(
            'qk_sender_id' => $_POST['sender_id'],
            'qk_receiver_id' => $_POST['receiver_id'],
            'qk_sj_id' => $_POST['sj_id'],
            'qk_bx_id' => $_POST['bx_id'],
            'qk_jl_id' => $_POST['jl_id'],
            'qk_type' => $_POST['type'], // pay : 1, reqest: 2
            'qk_stts' => 1,
            'qk_authcode' => $qk_code,
            'qk_ctime' => time(),
            'qk_utime' => time(),
            'qk_df' => 0
        );

        if (isset($_POST['atime'])) {
            $qingkeData['qk_atime'] = $_POST['atime'];
        }

        $qk_id = $this->qingke->create($qingkeData);

        if(gettype($qk_id) == "integer") {
            $time = time();
            $mjiu = json_decode($_POST['mjiu']);
            foreach($mjiu as $jiu) {
                $mjiuData = array(
                    'mjiu_atype' => 2,
                    'mjiu_action_id' => $qk_id,
                    'mjiu_type' => $jiu->jiu_type,
                    'mjiu_jiu_id' => $jiu->jiu_id,
                    'mjiu_count' => $jiu->jiu_count,
                    'mjiu_ctime' => $time,
                    'mjiu_utime' => $time,
                    'mjiu_df' => 0
                );

                $this->mjiu->create($mjiuData);
            }

            // if qingke type is request, send push to receiver;;;  pay : 1, reqest: 2
            if ($qingkeData["qk_type"] == 2) {

                $receiverData = $this->yonghu->getById($qingkeData["qk_receiver_id"]);
                $senderData = $this->yonghu->getById($qingkeData["qk_sender_id"]);

                // get shop owner device token
                $deviceToken = $receiverData[0]["yh_deviceId"];

                //$sentence = "您好！{$senderData[0]["yh_name"]} wants you to pay his dingdan";
                $sentence = " 用户<{$senderData[0]["yh_name"]}> 邀请您支付自己的订单";    
                
                $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openUserDingdanManagementPage", "123");
                $this->getui->pushActionToSingleAndroid($deviceToken, $sentence, "openUserDingdanManagementPage", "123");

            }

            $result['status'] = true;
            $result['data'] = $qk_code;
        } else {
            $result['status'] = false;
            $result['data'] = 'db error';
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array(
            'qk_utime' => time()
        );
        if (isset($_POST['accept'])) {
            $data['qk_accept'] = $_POST['accept'];
        }
        if (isset($_POST['stts'])) {
            $data['qk_stts'] = $_POST['stts'];
        }
        $this->qingke->update($data, $_POST['id']);

        $result['status'] = true;
        $result['data'] = '';

        echo json_encode($result);
    }
    /*
    stts_qk_pending = "1"
     stts_qk_progress = "2"
     stts_qk_received = "3"
     stts_qk_canceled = "4"
     stts_qk_completed = "5"
     stts_qk_denyed = "6"
     stts_qk_withdrawalrequested = "7"
    */
    public function getShangjiade() {
        $result = array();

        $stts = $_POST['status'];

        $data = array();

        if ($stts == 4 or $stts == 5 or $stts == 6) { // cancel, finished, denyed
            $stts = '4 or qk_stts=5 or qk_stts=6';
        } 
        else if ($stts == 2 or $stts == 3 or $stts == 7) { // progress, user completed, withdrawal requested
            $stts = '2 or qk_stts=3 or qk_stts=7';
        } 

        $ret = $this->qingke->getByShangjia($_POST['shangjiaId'], $stts);

        if (sizeof($ret) > 0) {
            foreach ($ret as $qk) {
                $qk['mjiu'] = array();

                $mjiu = $this->mjiu->getAll(2, $qk['qk_id']);
                foreach($mjiu as $mj) {
                    if($mj['mjiu_type'] == 1) {
                        $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                        if(sizeof($jiu) > 0) {
                            $jiu = $jiu[0];
                            $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                            $jiu['jiu_count'] = $mj['mjiu_count'];
                            $jiu['jiu_avatars'] = $avatars;

                            array_push($qk['mjiu'], $jiu);
                        }
                    } else if ($mj['mjiu_type'] == 2) {
                        $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                        if (sizeof($pack) > 0) {
                            $pack = $pack[0];
                            $pack['jiu_count'] = $mj['mjiu_count'];
                            array_push($qk['mjiu'], $pack);
                        }

                    }
                }
                array_push($data, $qk);
            }

            $result['status'] = true;
            $result['data'] = $data;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }

    public function getYonghude() {
        $result = array();
        $stts = $_POST['status'];
        $data = array();


        if ($stts == 1) { // pending
            $ret = $this->qingke->getPendingQingkesByYonghuId($_POST['yonghuId']);
        }
        else if ($stts == 2) { // progress
            $ret = $this->qingke->getProgressQingkesByYonghuId($_POST['yonghuId']);
        }
        else if ($stts == 5) { // completed
            $ret = $this->qingke->getCompletedQingkesByYonghuId($_POST['yonghuId']);
        }

        if (sizeof($ret) > 0) {
            foreach ($ret as $qk) {
                $qk['mjiu'] = array();

                $mjiu = $this->mjiu->getAll(2, $qk['qk_id']);
                foreach($mjiu as $mj) {
                    if($mj['mjiu_type'] == 1) {
                        $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                        if(sizeof($jiu) > 0) {
                            $jiu = $jiu[0];
                            $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                            $jiu['jiu_count'] = $mj['mjiu_count'];
                            $jiu['jiu_avatars'] = $avatars;

                            array_push($qk['mjiu'], $jiu);
                        }
                    } else if ($mj['mjiu_type'] == 2) {
                        $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                        if (sizeof($pack) > 0) {
                            $pack = $pack[0];
                            $pack['jiu_count'] = $mj['mjiu_count'];
                            array_push($qk['mjiu'], $pack);
                        }

                    }
                }
                array_push($data, $qk);
            }

            $result['status'] = true;
            $result['data'] = $data;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }


    // deny By ShangJia Owner : accept status： 5
    public function deny() {
        
        $result = array();
        $data = array();

        $data['qk_stts'] = 6; // denyed status
        $qkId = $_POST['qkId'];

        // update qingke status to accept status.
        $ret = $this->qingke->update($data, $qkId);

        if ($ret == true ) {

            // first get available information for push
            $data = $this->qingke->getDetailsById($qkId);

            $senderId = $data[0]['qk_sender_id'];
            $receiverId = $data[0]['qk_receiver_id'];

            $senderData = $this->yonghu->getById($senderId);
            $receiverData = $this->yonghu->getById($receiverId);

            // get shop owner device token
            $deviceToken = $senderData[0]["yh_deviceId"];
            //$sentence = "抱歉, yonghu<{$receiverData[0]["yh_name"]}> denyed your request.";
            $sentence = "抱歉, 用户<{$receiverData[0]["yh_name"]}> 拒绝了您的支付邀请";

            $this->getui->pushMessageToSingleIOS($deviceToken, $sentence);            

            $result['status'] = $ret;
            $result['data'] = 'success';
        }
        else {
            $result['status'] = $ret;
            $result['data'] = 'updating db failed';
        }
        

        echo json_encode($result);
    }

    // pay By user : paid status： 2
    public function pay() {
        
        $result = array();
        $data = array();

        $data['qk_stts'] = 2;
        $qkId = $_POST['qkId'];

        // update maijiu status to paid status.
        $ret = $this->qingke->update($data, $qkId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** please send dingdan to user <xx> .

            // first get available information for push
            $data = $this->qingke->getDetailsById($qkId);

            $senderId = $data[0]['qk_sender_id'];
            $receiverId = $data[0]['qk_receiver_id'];
            $shangjiaId = $data[0]['qk_sj_id'];

            $senderData = $this->yonghu->getById($senderId);
            $receiverData = $this->yonghu->getById($receiverId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken1 = $senderData[0]["yh_deviceId"];
            $deviceToken2 = $receiverData[0]["yh_deviceId"];
            $deviceToken3 = $shopOwnerData[0]["yh_deviceId"];

            // if qingke type is request, send push to receiver;;;  pay : 1, reqest: 2

            if ($data[0]['qk_type'] == 1 ) {
                //$sentence = "yonghu<{$senderData[0]["yh_name"]}> paid yonghu <{$receiverData[0]["yh_name"]}>, payment finished ";
               $sentence = "用户<{$senderData[0]["yh_name"]}>已经代用户<{$receiverData[0]["yh_name"]}>完成了支付";
            }
            else if ($data[0]['qk_type'] == 2 ) {
                //$sentence = "yonghu<{$receiverData[0]["yh_name"]}> accepted yonghu <{$senderData[0]["yh_name"]}> 's request, payment finished ";
                $sentence = "用户<{$receiverData[0]["yh_name"]}>接收用户<{$senderData[0]["yh_name"]}>的邀请并完成了支付 ";
            }

            // send push to 3 persons
            $deviceTokenList = array($deviceToken1, $deviceToken2, $deviceToken3 );
            $this->getui->pushMessageToMulti($deviceTokenList, $sentence, $sentence);

            $result['status'] = $ret;
            $result['data'] = 'success';
        }
        else {
            $result['status'] = $ret;
            $result['data'] = 'updating db failed';
        }
        

        echo json_encode($result);
    }


    // complete By user : user completed status： 3
    public function completeByUser() {
        
        $result = array();
        $data = array();

        $data['qk_stts'] = 3;
        $qkId = $_POST['qkId'];

        // update maijiu status to paid status.
        $ret = $this->qingke->update($data, $qkId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** please send dingdan to user <xx> .

            // first get available information for push
            $data = $this->qingke->getDetailsById($qkId);

            $senderId = $data[0]['qk_sender_id'];
            $receiverId = $data[0]['qk_receiver_id'];
            $shangjiaId = $data[0]['qk_sj_id'];

            $senderData = $this->yonghu->getById($senderId);
            $receiverData = $this->yonghu->getById($receiverId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken1 = $senderData[0]["yh_deviceId"];
            $deviceToken2 = $receiverData[0]["yh_deviceId"];
            $deviceToken3 = $shopOwnerData[0]["yh_deviceId"];

            if ($data[0]['qk_type'] == 1 ) { // pay
                $sentence = "用户 <{$receiverData[0]["yh_name"]}> 确认了交易完成， 您可以请求提现了。";
            }
            else if ($data[0]['qk_type'] == 2 ) { // request
                $sentence = "用户 <{$senderData[0]["yh_name"]}> 确认了交易完成， 您可以请求提现了。";
            }

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            
            $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openShopDingdanManagementPage", "123");

            $result['status'] = $ret;
            $result['data'] = 'success';
        }
        else {
            $result['status'] = $ret;
            $result['data'] = 'updating db failed';
        }
        

        echo json_encode($result);
    }






}