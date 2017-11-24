<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/23/2017
 * Time: 1:11 AM
 */
class Fujin extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Fujin_model', 'fujin');
        $this->load->model('Dingzuo_model', 'dingzuo');
        $this->load->model('Mjiu_model', 'mjiu');
        $this->load->model('Yonghu_model', 'yonghu');
        $this->load->model('Jiu_model', 'jiu');
        $this->load->model('Shangjia_model', 'shangjia');

        $this->load->library('Getui', 'getui');
    }

    public function create() {
        $result = array();

        $fujinData = array(
            'fj_sender_id' => $_POST['sender_id'],
            'fj_receiver_id' => $_POST['receiver_id'],
            'fj_sj_id' => $_POST['sj_id'],
            'fj_bx_id' => $_POST['bx_id'],
            'fj_jl_id' => $_POST['jl_id'],
            'fj_stts' => 1,
            'fj_ctime' => time(),
            'fj_utime' => time(),
            'fj_df' => 0
        );

        if (isset($_POST['atime'])) {
            $fujinData['fj_atime'] = $_POST['atime'];
        }

        $fj_id = $this->fujin->create($fujinData);

        if(gettype($fj_id) == "integer") {
            $time = time();
            $mjiu = json_decode($_POST['mjiu']);
            foreach($mjiu as $jiu) {
                $mjiuData = array(
                    'mjiu_atype' => 3,
                    'mjiu_action_id' => $fj_id,
                    'mjiu_type' => $jiu->jiu_type,
                    'mjiu_jiu_id' => $jiu->jiu_id,
                    'mjiu_count' => $jiu->jiu_count,
                    'mjiu_ctime' => $time,
                    'mjiu_utime' => $time,
                    'mjiu_df' => 0
                );

                $this->mjiu->create($mjiuData);
            }

            $result['status'] = true;
            $result['data'] = $fj_id;
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
            $data['fj_accept'] = $_POST['accept'];
        }
        if (isset($_POST['stts'])) {
            $data['fj_stts'] = $_POST['stts'];
        }
        $this->fujin->update($data, $_POST['id']);

        $result['status'] = true;
        $result['data'] = '';

        echo json_encode($result);
    }

    /*
        1: pending(create), 2: approved(pay), 2: completed by shangjia(completeRequest),
        3: complete by user(completeByUser), 4: cancelled by user(cancelByUser), 5: withdraw
    */
    public function getShangjiade() {
        $result = array();

        $stts = $_POST['status'];

        if ($stts == 2) {
            $stts = '2 or fj_stts=3';
        }

        /*
        if ($stts == 4) {
            $stts = '4 or fj_stts=5';
        }
        */

        $ret = $this->fujin->getByShangjia($_POST['shangjiaId'], $stts);

        $data = array();
        if (sizeof($ret) > 0) {
            foreach ($ret as $fj) {
                $fj['mjiu'] = array();

                $mjiu = $this->mjiu->getAll(3, $fj['fj_id']);
                foreach($mjiu as $mj) {
                    if($mj['mjiu_type'] == 1) {
                        $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                        if(sizeof($jiu) > 0) {
                            $jiu = $jiu[0];
                            $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                            $jiu['jiu_count'] = $mj['mjiu_count'];
                            $jiu['jiu_avatars'] = $avatars;

                            array_push($fj['mjiu'], $jiu);
                        }
                    } else if ($mj['mjiu_type'] == 2) {
                        $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                        if (sizeof($pack) > 0) {
                            $pack = $pack[0];
                            $pack['jiu_count'] = $mj['mjiu_count'];
                            array_push($fj['mjiu'], $pack);
                        }

                    }
                }
                array_push($data, $fj);
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

        if ($stts == 3) {
            $stts = '3 or fj_stts=4 or fj_stts=5';
        }

        $data = array();
        $ret = $this->fujin->getByYonghu($_POST['yonghuId'], $stts);
        if (sizeof($ret) > 0) {
            foreach ($ret as $fj) {
                $fj['mjiu'] = array();

                $mjiu = $this->mjiu->getAll(3, $fj['fj_id']);
                foreach($mjiu as $mj) {
                    if($mj['mjiu_type'] == 1) {
                        $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                        if(sizeof($jiu) > 0) {
                            $jiu = $jiu[0];
                            $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                            $jiu['jiu_count'] = $mj['mjiu_count'];
                            $jiu['jiu_avatars'] = $avatars;

                            array_push($fj['mjiu'], $jiu);
                        }
                    } else if ($mj['mjiu_type'] == 2) {
                        $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                        if (sizeof($pack) > 0) {
                            $pack = $pack[0];
                            $pack['jiu_count'] = $mj['mjiu_count'];
                            array_push($fj['mjiu'], $pack);
                        }

                    }
                }
                array_push($data, $fj);
            }

            $result['status'] = true;
            $result['data'] = $data;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }

    // Sender pays here and status - 2, send push to receiver
    public function pay() {
        $result = array();
        $data = array();

        $data['fj_stts'] = 2;
        $fjId = $_POST['fjId'];

        // update maijiu status to paid status.
        $ret = $this->fujin->update($data, $fjId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** please send dingdan to user <xx> .

            // first get available information for push
            $data = $this->fujin->getDetailsById($fjId);

            $senderId = $data[0]['fj_sender_id'];
            $receiverId = $data[0]['fj_receiver_id'];
            $shangjiaId = $data[0]['fj_sj_id'];

            $senderData = $this->yonghu->getById($senderId);
            $receiverData = $this->yonghu->getById($receiverId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $receiverData[0]["yh_deviceId"];

            // make push sentence. e.g <xx> sent you jiu
            $sentence = "<{$senderData[0]['yh_name']}> sent you jiu";

            $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openShopDingdanManagementPage", "123");
            $this->getui->pushActionToSingleAndroid($deviceToken, $sentence, "openShopDingdanManagementPage", "123");

            $result['status'] = $ret;
            $result['data'] = 'success';
        }
        else {
            $result['status'] = $ret;
            $result['data'] = 'updating db failed';
        }


        echo json_encode($result);
    }

    // Sender cancels dingdan before pay - 4
    public function cancel() {
        $result = array();
        $data = array();

        $data['fj_stts'] = 4;
        $fjId = $_POST['fjId'];

        // update maijiu status to user side completed status.
        $ret = $this->fujin->update($data, $fjId);

        if ($ret == true ) {
            $result['status'] = true;
            $result['data'] = 'success';
        } else {
            $result['status'] = false;
            $result['data'] = 'db error';
        }

        echo json_encode($result);
    }

    // Shangjia send complete request - 2
    public function completeRequest() {
        $result = array();

        $fjId = $_POST['fjId'];

        // first get available information for push
        $data = $this->fujin->getDetailsById($fjId);

        $buyerId = $data[0]['fj_sender_id'];
        $shangjiaId = $data[0]['fj_sj_id'];

        $buyerData = $this->yonghu->getById($buyerId);
        $shopData = $this->shangjia->detail($shangjiaId);

        // get shop owner send complete request to sender
        $deviceToken = $buyerData[0]["yh_deviceId"];
        $sentence = "Shop <{$shopData[0]["sj_name"]}> owner wants you to complete current jiaoyi";

        $result['status'] = true;
        $result['data'] = 'success';
        echo json_encode($result);

        $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openUserDingdanManagementPage", "123");
        $this->getui->pushActionToSingleAndroid($deviceToken, $sentence, "openUserDingdanManagementPage", "123");
    }

    // User complete jiaoyi - 3
    public function completeByUser() {
        $result = array();
        $data = array();

        $data['fj_stts'] = 3;
        $fjId = $_POST['fjId'];

        // update fujin status to user side completed status.
        $ret = $this->fujin->update($data, $fjId);

        if ($ret == true ) {
            // first get available information for push
            $data = $this->fujin->getDetailsById($fjId);

            $buyerId = $data[0]['fj_sender_id'];
            $shangjiaId = $data[0]['fj_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];

            // make push sentence. e.g  user <xx> confirmed jiaoyi finished, you can request withdrawal
            $sentence = "user<{$buyerData[0]["yh_name"]}> confirmed jiaoyi finished, you can request withdrawal";

            $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openShopDingdanManagementPage", "123");
            $this->getui->pushActionToSingleAndroid($deviceToken, $sentence, "openShopDingdanManagementPage", "123");

            $result['status'] = $ret;
            $result['data'] = 'success';
        }
        else {
            $result['status'] = $ret;
            $result['data'] = 'updating db failed';
        }


        echo json_encode($result);
    }

    // Shop owner confirm jiaoyi and send withdrawal request - 5
    public function withdrawalRequest() {

        $result = array();
        $data = array();

        $data['fj_stts'] = 5; // withdrawal requested status
        $fjId = $_POST['fjId'];

        // update maijiu status to withdrawal status.
        $ret = $this->fujin->update($data, $fjId);

        if ($ret == true ) {
            // here send sms to xiaoer
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