<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/21/2017
 * Time: 11:41 PM
 */
class Zaijiahe extends MY_Controller {

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

    public function getYonghude() {
        $result = array();

        $stts = $_POST['status'];

        /* 1: pending 2: approved 3: delivery, 4: received  5: completed 6: canceled */
        //progress list  2, 3
        if ($stts == 2 or $stts == 3) {
            $stts = "2 or mj.mj_stts = 3 ";
        }
        else if ($stts == 4 or $stts == 5) {
            $stts = "4 or mj.mj_stts = 5 ";
        }



        $data = array();
        $zaijiahe = $this->maijiu->getZaijiaheByYonghu($_POST['yonghuId'], $stts);
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

        /* 1: pending 2: approved 3: delivery, 4: received  5: completed 6: canceled  7: withdrawal requested */
        //progress list  2, 3, 4
        if ($stts == 2 or $stts == 3 or $stts == 4 or $stts == 7 ) {
            $stts = "2 or mj.mj_stts = 3 or mj.mj_stts = 4 or mj.mj_stts = 7 ";
        }
        else if ($stts == 5 or $stts == 6) {
            $stts = "5 or mj.mj_stts = 6 ";
        }

        $data = array();
        $zaijiahe = $this->maijiu->getZaijiaheByShangjia($_POST['shangjiaId'], $stts);
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
        $zjhId = $_POST['zjhId'];

        // update maijiu status to paid status.
        $ret = $this->maijiu->update($data, $zjhId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** please send dingdan to user <xx> .

            // first get available information for push
            $data = $this->maijiu->getDetailsById($zjhId);

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


    // start delivery By shop owner : delivery status： 3
    public function startDelivery() {
        
        $result = array();
        $data = array();

        $data['mj_stts'] = 3;
        $zjhId = $_POST['zjhId'];
        $carrierName = $_POST['carrierName'];
        $carrierPhone = $_POST['carrierPhone'];

        // update maijiu status to delivery status.
        $ret = $this->maijiu->update($data, $zjhId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** Shop <shopname> owner start delivery .

            // first get available information for push
            $data = $this->maijiu->getDetailsById($zjhId);

            $buyerId = $data[0]['mj_buyer_id'];
            $shangjiaId = $data[0]['mj_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopData = $this->shangjia->detail($shangjiaId);

            // get shop owner device token
            $deviceToken = $buyerData[0]["yh_deviceId"];
            $sentence = "Shop <{$shopData[0]["sj_name"]}> owner start delivery";

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

    // user received delivery from carrier : received status： 4
    public function receiveDelivery() {
        
        $result = array();
        $data = array();

        $data['mj_stts'] = 4;
        $zjhId = $_POST['zjhId'];

        // update maijiu status to delivery status.
        $ret = $this->maijiu->update($data, $zjhId);

        if ($ret == true ) {
            // here send push to shop owner ;; *** Shop <shopname> owner start delivery .

            // first get available information for push
            $data = $this->maijiu->getDetailsById($zjhId);

            $buyerId = $data[0]['mj_buyer_id'];
            $shangjiaId = $data[0]['mj_sj_id'];

            $buyerData = $this->yonghu->getById($buyerId);
            $shopOwnerData = $this->yonghu->getByShangjiaId($shangjiaId);

            // get shop owner device token
            $deviceToken = $shopOwnerData[0]["yh_deviceId"];
            
            // make push sentence. e.g  user <xx> received delivery, you can request withdrawal
            $sentence = "user<{$buyerData[0]["yh_name"]}> received delivery, you can request withdrawal";

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


    // sent receive check request By ShangJia Owner : simple push :)
    public function receiveRequest() {
        
        $result = array();

        $zjhId = $_POST['zjhId'];

        // here send push to user ;; *** shop owner accepted your dingzuo request.

        // first get available information for push
        $data = $this->maijiu->getDetailsById($zjhId);

        $buyerId = $data[0]['mj_buyer_id'];
        $shangjiaId = $data[0]['mj_sj_id'];

        $buyerData = $this->yonghu->getById($buyerId);
        $shopData = $this->shangjia->detail($shangjiaId);

        // get shop owner device token
        $deviceToken = $buyerData[0]["yh_deviceId"];
        $sentence = "Shop <{$shopData[0]["sj_name"]}> owner wants you to confirm received delivery";

        $result['status'] = true;
        $result['data'] = 'success';
        echo json_encode($result);

        $this->getui->pushActionToSingleIOS($deviceToken, $sentence, "openUserDingdanManagementPage", "123");
        
    }

    // send withdrawal request
    public function withdrawalRequest() {

        $result = array();
        $data = array();

        $data['mj_stts'] = 7; // withdrawal requested status
        $zjhId = $_POST['zjhId'];

        // update maijiu status to delivery status.
        $ret = $this->maijiu->update($data, $zjhId);

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