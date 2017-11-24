<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/11/2017
 * Time: 1:17 PM
 */
class Maijiu extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MaiJiu_model', 'maijiu');
        $this->load->model('Mjiu_model', 'mjiu');
        $this->load->model('Jiu_model', 'jiu');
        $this->load->model('Pack_model', 'pack');
        $this->load->model('Yonghu_model', 'yonghu');
        $this->load->model('Shangjia_model', 'shangjia');

        $this->load->library('Getui', 'getui');
    }

    public function create()
    {
        $result = array();

        $data = array(
            'mj_buyer_id' => $_POST['buyerId'],
            'mj_type' => $_POST['type'],
            'mj_sj_id' => $_POST['shangjiaId'],
            'mj_bx_id' => $_POST['baoxiangId'],
            'mj_jl_id' => $_POST['jingliId'],
            'mj_price' => $_POST['price'],
            'mj_aprd' => 0,
            'mj_stts' => 1,
            'mj_ctime' => time(),
            'mj_utime' => time(),
            'mj_df' => 0
        );

        if (isset($_POST['atime'])) {
            $data['mj_atime'] = $_POST['atime'];
        }

        $ret = $this->maijiu->create($data);
        if (gettype($ret) == "boolean") {
            $result['status'] = false;
            $result['data'] = "db error";
        } else {
            $time = time();
            $mjiu = json_decode($_POST['mjiu']);
            foreach($mjiu as $jiu) {
                $mjiuData = array(
                    'mjiu_atype' => 1,
                    'mjiu_action_id' => $ret,
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
            $result['data'] = $ret;
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array();

        if (isset($_POST['aprd'])) {
            $data['mj_aprd'] = $_POST['aprd'];
        }
        if (isset($_POST['status'])) {
            $data['mj_stts'] = $_POST['status'];
        }

        $ret = $this->maijiu->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }

    public function getYonghude() {
        $result = array();

        $stts = $_POST['status'];

        /* 1: pending 2: progress  5: completed 6: canceled 4: user side finished， 7: withdrawalrequest*/
        if ($stts == 5 or $stts == 4 or $stts == 6 ) {
            $stts = "5 or mj.mj_stts = 4 or mj.mj_stts = 6 or mj.mj_stts = 7";
        }

        $data = array();
        $zaijiahe = $this->maijiu->getMaijiuByYonghu($_POST['yonghuId'], $stts);
        foreach ($zaijiahe as $zj) {
            $zj['mjiu'] = array();

            $mjiu = $this->mjiu->getAll(1, $zj['mj_id']);
            foreach($mjiu as $mj) {
                if($mj['mjiu_type'] == 1) {
                    $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                    if(sizeof($jiu) > 0) {
                        $jiu = $jiu[0];
                        $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                        $jiu['jiu_count'] = $mj['mjiu_count'];
                        $jiu['jiu_avatars'] = $avatars;

                        array_push($zj['mjiu'], $jiu);
                    }
                } else if ($mj['mjiu_type'] == 2) {
                    $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                    if (sizeof($pack) > 0) {
                        $pack = $pack[0];
                        $pack['jiu_count'] = $mj['mjiu_count'];
                        array_push($zj['mjiu'], $pack);
                    }

                }
            }
            array_push($data, $zj);
        }

        $result['status'] = true;
        $result['data'] = $data;

        echo json_encode($result);

    }


    public function getShangjiade() {
        $result = array();

        $stts = $_POST['status'];

        /* 1: pending 2: progress  5: completed 6: canceled 4: user side finished ， 7: withdrawalrequest*/
        //progress list  2,  4
        if ($stts == 2 or $stts == 4 or $stts == 7 ) {
            $stts = "2 or mj.mj_stts = 4 or mj.mj_stts = 7 ";
        }

        $data = array();
        $zaijiahe = $this->maijiu->getMaijiuByShangjia($_POST['shangjiaId'], $stts);
        foreach ($zaijiahe as $zj) {
            $zj['mjiu'] = array();

            $mjiu = $this->mjiu->getAll(1, $zj['mj_id']);
            foreach($mjiu as $mj) {
                if($mj['mjiu_type'] == 1) {
                    $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                    if(sizeof($jiu) > 0) {
                        $jiu = $jiu[0];
                        $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                        $jiu['jiu_count'] = $mj['mjiu_count'];
                        $jiu['jiu_avatars'] = $avatars;

                        array_push($zj['mjiu'], $jiu);
                    }
                } else if ($mj['mjiu_type'] == 2) {
                    $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                    if (sizeof($pack) > 0) {
                        $pack = $pack[0];
                        $pack['jiu_count'] = $mj['mjiu_count'];
                        array_push($zj['mjiu'], $pack);
                    }

                }
            }
            array_push($data, $zj);
        }

        $result['status'] = true;
        $result['data'] = $data;

        echo json_encode($result);

    }

    // pay By user : paid status： 2
    public function pay() {
        
        $result = array();
        $data = array();

        $data['mj_stts'] = 2;
        $mjId = $_POST['mjId'];

        // update maijiu status to paid status.
        $ret = $this->maijiu->update($data, $mjId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** please send dingdan to user <xx> .

            // first get available information for push
            $data = $this->maijiu->getDetailsById($mjId);

            $buyerId = $data[0]['mj_buyer_id'];
            $shangjiaId = $data[0]['mj_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            
            // make push sentence. e.g  please send dingdan to user <xx>, he already paid
            $sentence = "please send dingdan to user<{$buyerData[0]["yh_name"]}>, he already paid";

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


    // shop owner send complete request to user : simple push
    public function completeRequest() {
        $result = array();

        $mjId = $_POST['mjId'];

        // here send push to user ;; *** shop owner accepted your dingzuo request.

        // first get available information for push
        $data = $this->maijiu->getDetailsById($mjId);

        $buyerId = $data[0]['mj_buyer_id'];
        $shangjiaId = $data[0]['mj_sj_id'];

        $buyerData = $this->yonghu->getById($buyerId);
        $shopData = $this->shangjia->detail($shangjiaId);

        // get shop owner device token
        $deviceToken = $buyerData[0]["yh_deviceId"];
        $sentence = "Shop <{$shopData[0]["sj_name"]}> owner wants you to complete current jiaoyi";

        $result['status'] = true;
        $result['data'] = 'success';
        echo json_encode($result);

        //$this->getui->pushMessageToSingleIOS($deviceToken, $sentence);
        $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openUserDingdanManagementPage", "123");
    }

    public function completeUserSide()
    {
        $result = array();
        $data = array();

        $data['mj_stts'] = 4;
        $mjId = $_POST['mjId'];

        // update maijiu status to user side completed status.
        $ret = $this->maijiu->update($data, $mjId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** Shop <shopname> owner start delivery .

            // first get available information for push
            $data = $this->maijiu->getDetailsById($mjId);

            $buyerId = $data[0]['mj_buyer_id'];
            $shangjiaId = $data[0]['mj_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            
            // make push sentence. e.g  user <xx> confirmed jiaoyi finished, you can request withdrawal
            $sentence = "user<{$buyerData[0]["yh_name"]}> confirmed jiaoyi finished, you can request withdrawal";

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

    // send withdrawal request
    public function withdrawalRequest() {

        $result = array();
        $data = array();

        $data['mj_stts'] = 7; // withdrawal requested status
        $mjId = $_POST['mjId'];

        // update maijiu status to withdrawal status.
        $ret = $this->maijiu->update($data, $mjId);

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