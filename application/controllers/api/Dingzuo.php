<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 5:47 PM
 */
class Dingzuo extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dingzuo_model', 'dingzuo');
        $this->load->model('Shangjia_model', 'shangjia');
        $this->load->model('Yonghu_model', 'yonghu');
        $this->load->model('Baoxiang_model', 'baoxiang');
        $this->load->model('Jingli_model', 'jingli');

        $this->load->library('Getui', 'getui');
    }

    public function create() {
        $result = array();

        $data = array(
            'dz_buyer_id' => $_POST['buyerId'],
            'dz_sj_id' => $_POST['shangjiaId'],
            'dz_bx_id' => $_POST['baoxiangId'],
            'dz_atime' => $_POST['atime'],
            'dz_pcount' => $_POST['pcount'],
            'dz_jl_id' => $_POST['jingliId'],
            'dz_stts' => 1, // pendingf
            'dz_ctime' => time(),
            'dz_utime' => time(),
            'dz_df' => 0
        );

        $ret = $this->dingzuo->create($data);
        if (gettype($ret) == "boolean") {
            $result['status'] = false;
            $result['data'] = "db error";
        } else {
            $result['status'] = true;
            $result['data'] = $ret;
            $this->sendPushtoShopOwner($data, $ret);
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array();

        if (isset($_POST['status'])) {
            $data['dz_stts'] = $_POST['status'];
        }

        $ret = $this->dingzuo->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }

    public function getShangjiade() {
        $result = array();
        $sj_id = $_POST['shangjiaId'];
        $stts = $_POST['status'];

        if ($stts == 3) { // complteted status, include 4 (cancel), 5 (deny)
            $stts = "3 or dz.dz_stts = 4 or dz.dz_stts = 5 ";
        }

        $res = $this->dingzuo->getShangjiade($sj_id, $stts);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function getYonghude() {
        $result = array();
        $yh_id = $_POST['yonghuId'];
        $stts = $_POST['status'];

        if ($stts == 3) { // complteted status, include 4 (cancel), 5 (deny)
            $stts = "3 or dz.dz_stts = 4 or dz.dz_stts = 5 ";
        }

        $res = $this->dingzuo->getYonghude($yh_id, $stts);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function detail($dz_id) {

    }

    // send notification to shop owner with Yuding request.
    public function sendPushtoShopOwner($data, $yudingId) {

        $buyerId = $data['dz_buyer_id'];
        $shangjiaId = $data['dz_sj_id'];
        $baoxiangId = $data['dz_bx_id'];
        $atime = $data['dz_atime'];
        $pcount = $data['dz_pcount'];
        $jingliId = $data['dz_jl_id'];

        // get data from request
        $buyerData = $this->yonghu->getById($buyerId);
        $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);
        $baoxiangData = $this->baoxiang->getById( $baoxiangId);
        $jingliData = $this->jingli->getById($jingliId);

        // get shop owner device token
        $deviceToken = $shopOwnerData[0]["yh_deviceId"];
        //$deviceToken = "dbccac9f47ed0a912a89a085c27122d738c53ff164485f04ab57a78d437139da";

        // make push sentence. e.g user<xx> request to yuding Baoxiang/Kazuo <xxx> of your Restaurant. Accept/Deny ?
        $sentence = "user<{$buyerData[0]["yh_name"]}> request to yuding ";

        // check baoxiang or kazuo 1: 包厢   2: 卡座
        if ( $baoxiangData[0]["bx_type"] == 1 ) { // baoxiang
            $sentence = $sentence . "Baoxiang<{$baoxiangData[0]["bx_name"]}> of your Restaurant. ";
        }
        else if ( $baoxiangData[0]["bx_type"] == 2 ) {
            $sentence = $sentence . "Kazuo<{$baoxiangData[0]["bx_name"]}> of your Restaurant. ";
        }

        // check jingli
        if ($jingliId != 0) { // if user select jingli
            $sentence = $sentence . "he want jingli <{$jingliData[0]["jl_name"]}>.";
        }

        $sentence = $sentence . "pcount; {$pcount} , atime: {$atime}";

        $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "yudingId", $yudingId);
        $this->getui->pushActionToSingleAndroid($deviceToken, $sentence, "yudingId", $yudingId);
    }

    public function send() {
        $this->getui->pushMessageToSingleAndroid('f7a4d9dd84ee84fe72bb80274b5137e8', 'testing', "yudingId", 1);
    }

    public function sendPushtoMulti() {

        $deviceToken = "dbccac9f47ed0a912a89a085c27122d738c53ff164485f04ab57a78d437139da";
        $sixDeviceToken = "65f0afad45dfa0724174ae3a4e19589eb6c4d6725f1fccff111bcb15a7ac0df3";
        $androidToken = "10a0fc89eb34e6a2b43517afda710632";
        $deviceTokenList = array($deviceToken,$androidToken );

        $this->getui->pushMessageToMulti($deviceTokenList, "Hello, title", "Hello, Information");
    }


    // cancel by user : cancel status： 4
    public function cancelByUser() {
        
        $result = array();
        $data = array();

        $data['dz_stts'] = 4;
        $dingzuoId = $_POST['dingzuoId'];

        // update dingzuo status to canceled status.
        $ret = $this->dingzuo->update($data, $dingzuoId);

        if ($ret == true ) {
            // here send push to sj master ;; buyer canceled his dingzuo request.

            // first get available information for push
            $data = $this->dingzuo->getDetailsById($dingzuoId);

            $buyerId = $data[0]['dz_buyer_id'];
            $shangjiaId = $data[0]['dz_sj_id'];
            $baoxiangId = $data[0]['dz_bx_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);
            $baoxiangData = $this->baoxiang->getById( $baoxiangId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            $sentence = "user<{$buyerData[0]["yh_name"]}> canceled dingzuo request";

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



    // accept By ShangJia Owner : accept status： 2
    public function accept() {
        
        $result = array();
        $data = array();

        $data['dz_stts'] = 2;
        $dingzuoId = $_POST['dingzuoId'];

        // update dingzuo status to accept status.
        $ret = $this->dingzuo->update($data, $dingzuoId);

        if ($ret == true ) {
            // here send push to user ;; *** shop owner accepted your dingzuo request.

            // first get available information for push
            $data = $this->dingzuo->getDetailsById($dingzuoId);

            $buyerId = $data[0]['dz_buyer_id'];
            $shangjiaId = $data[0]['dz_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopData = $this->shangjia->detail($shangjiaId);

            // get shop owner device token
            $deviceToken = $buyerData[0]["yh_deviceId"];
            $sentence = "Shop <{$shopData[0]["sj_name"]}> owner accepted your dingzuo request";

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

    // deny By ShangJia Owner : accept status： 5
    public function deny() {
        
        $result = array();
        $data = array();

        $data['dz_stts'] = 5;
        $dingzuoId = $_POST['dingzuoId'];

        // update dingzuo status to accept status.
        $ret = $this->dingzuo->update($data, $dingzuoId);

        if ($ret == true ) {
            // here send push to user ;; *** shop owner accepted your dingzuo request.

            // first get available information for push
            $data = $this->dingzuo->getDetailsById($dingzuoId);

            $buyerId = $data[0]['dz_buyer_id'];
            $shangjiaId = $data[0]['dz_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopData = $this->shangjia->detail($shangjiaId);

            // get shop owner device token
            $deviceToken = $buyerData[0]["yh_deviceId"];
            $sentence = "Shop <{$shopData[0]["sj_name"]}> owner denyed your dingzuo request because there are some issues.";

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

    // sent complete request By ShangJia Owner : simple push :)
    public function completerequest() {
        
        $result = array();

        $dingzuoId = $_POST['dingzuoId'];

        // here send push to user ;; *** shop owner accepted your dingzuo request.

        // first get available information for push
        $data = $this->dingzuo->getDetailsById($dingzuoId);

        $buyerId = $data[0]['dz_buyer_id'];
        $shangjiaId = $data[0]['dz_sj_id'];

        $buyerData = $this->yonghu->getById($buyerId);
        $shopData = $this->shangjia->detail($shangjiaId);

        // get shop owner device token
        $deviceToken = $buyerData[0]["yh_deviceId"];
        $sentence = "Shop <{$shopData[0]["sj_name"]}> owner wants you to complete current jiaoyi";

        $result['status'] = true;
        $result['data'] = 'success';
        echo json_encode($result);

        //$this->getui->pushMessageToSingleIOS($deviceToken, $sentence);
        $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "dingzuoId", $dingzuoId);
        
    }


    // complete By user : completed status： 3
    public function complete() {
        
        $result = array();
        $data = array();

        $data['dz_stts'] = 3;
        $dingzuoId = $_POST['dingzuoId'];

        // update dingzuo status to accept status.
        $ret = $this->dingzuo->update($data, $dingzuoId);

        if ($ret == true ) {
            // here send push to user ;; *** shop owner accepted your dingzuo request.

            // first get available information for push
            $data = $this->dingzuo->getDetailsById($dingzuoId);

            $buyerId = $data[0]['dz_buyer_id'];
            $shangjiaId = $data[0]['dz_sj_id'];
            $baoxiangId = $data[0]['dz_bx_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);
            $baoxiangData = $this->baoxiang->getById( $baoxiangId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            $sentence = "user<{$buyerData[0]["yh_name"]}> completed dingzuo jiaoyi - baoxiangName is {$baoxiangData[0]["bx_name"]}";

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


}