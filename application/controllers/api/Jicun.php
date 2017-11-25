<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 10:05 PM
 * 
 */

class Jicun extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Jicun_model', 'jicun');
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
            'jc_saver_id' => $_POST['saverId'],
            'jc_contact' => $_POST['contact'],
            'jc_sj_id' => $_POST['shangjiaId'],
            'jc_bx_id' => $_POST['baoxiangId'],
            'jc_jl_id' => $_POST['jingliId'],
            'jc_savingtime' => $_POST['savingTime'],
            'jc_signed' => 0,
            'jc_aprd' => 0,
            'jc_stts' => 1,
            'jc_ctime' => time(),
            'jc_utime' => time(),
            'jc_df' => 0
        );

        $ret = $this->jicun->create($data);
        if (gettype($ret) == "boolean") {
            $result['status'] = false;
            $result['data'] = "db error";
        } else {
            $result['status'] = true;
            $result['data'] = $ret;

            // send push to shop owner to accept created jicun
            
            $yonghuId = $_POST['saverId'];
            $shangjiaId = $_POST['shangjiaId'];

            $buyerData = $this->yonghu->getById($yonghuId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            //$sentence = "user<{$buyerData[0]["yh_name"]}> created jicun.";
            $sentence = "用户<{$buyerData[0]["yh_name"]}> 创建了寄存单。请受理。";

            $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openShopJicunManagementPage", "123");

        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array();

        if (isset($_POST['aprd'])) {
            $data['jc_aprd'] = $_POST['aprd'];
        }
        if (isset($_POST['status'])) {
            $data['jc_stts'] = $_POST['status'];
        }
        if (isset($_POST['savingtime'])) {
            $data['jc_savingtime'] = $_POST['savingtime'];
        }
        if (isset($_POST['signed'])) {
            $data['jc_signed'] = $_POST['signed'];
        }
        if (isset($_POST['df'])) {
            $data['jc_df'] = 1;
        }

        $ret = $this->jicun->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }


    // send push notification to shop owner about jicun create request. available info: username, bx name,
    public function createRequest() {
        $result = array();
        $data = array();

        $yonghuId = $_POST['yonghuId'];
        $shangjiaId = $_POST['shangjiaId'];
        $bxName = $_POST['bxName'];

        $buyerData = $this->yonghu->getById($yonghuId);
        $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

        // get shop owner device token
        $deviceToken = $shopOwnerData[0]["yh_deviceId"];
        //$sentence = "user<{$buyerData[0]["yh_name"]}> wants jicun, room name : {$bxName}";
        $sentence = "用户<{$buyerData[0]["yh_name"]}> 想寄存，包厢是 {$bxName}。";

        $this->getui->pushMessageToSingleIOS($deviceToken, $sentence);

        $result['status'] = true;
        $result['data'] = 'success';


        echo json_encode($result);

    }   


    public function getShangjiade() {
        $result = array();
        $sj_id = $_POST['shangjiaId'];

        $res = $this->jicun->getShangjiade($sj_id);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);

        $result = array();

    }

    public function getYonghude() {
        $result = array();
        $yh_id = $_POST['yonghuId'];

        $res = $this->jicun->getYonghude($yh_id);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

/*   pending = "1"  progress = "2" completed = "3" canceled = "4" expired = "5" backrequested = "7"*/
    public function accept() {
        $result = array();
        $data = array();

        $data['jc_stts'] = 2;
        $jcId = $_POST['jcId'];

        // update maijiu status to accpeted status.
        $ret = $this->jicun->update($data, $jcId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** please send dingdan to user <xx> .

            // first get available information for push
            $data = $this->jicun->getDetailsById($jcId);

            $buyerId = $data[0]['jc_saver_id'];
            $shangjiaId = $data[0]['jc_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopData = $this->shangjia->detail($shangjiaId);

            // get shop owner device token
            $deviceToken = $buyerData[0]["yh_deviceId"];
            //$sentence = "商家 <{$shopData[0]["sj_name"]}> owner accepted your jicun";
            $sentence = "商家 <{$shopData[0]["sj_name"]}> 接收了您的寄存。";
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


    public function backRequest() {
        $result = array();
        $data = array();

        /*   pending = "1"  progress = "2" completed = "3" canceled = "4" expired = "5" backrequested = "7"*/
        $data['jc_stts'] = 7;
        $jcId = $_POST['jcId'];

        // update maijiu status to accpeted status.
        $ret = $this->jicun->update($data, $jcId);

        if ($ret == true ) {

            // first get available information for push
            $data = $this->jicun->getDetailsById($jcId);

            $buyerId = $data[0]['jc_saver_id'];
            $shangjiaId = $data[0]['jc_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopData = $this->shangjia->detail($shangjiaId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            //$sentence = "user<{$buyerData[0]["yh_name"]}> wants quchu jicun";
            $sentence = "用户<{$buyerData[0]["yh_name"]}> 想取寄存。";    
            $this->getui->pushMessageToSingleIOS($deviceToken, $sentence);

            $result['status'] = true;
            $result['data'] = 'success';
        }
        else {
            $result['status'] = $ret;
            $result['data'] = 'updating db failed';
        }
        
        echo json_encode($result);
    }

    public function complete() {
        $result = array();
        $data = array();

        $data['jc_stts'] = 3;
        $jcId = $_POST['jcId'];

        // update maijiu status to accpeted status.
        $ret = $this->jicun->update($data, $jcId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** please send dingdan to user <xx> .
            // first get available information for push
            $data = $this->jicun->getDetailsById($jcId);

            $buyerId = $data[0]['jc_saver_id'];
            $shangjiaId = $data[0]['jc_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopData = $this->shangjia->detail($shangjiaId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
         //   $sentence = "user<{$buyerData[0]["yh_name"]}> completed jicun";
              $sentence = "用户<{$buyerData[0]["yh_name"]}> 完成了取寄存。";

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