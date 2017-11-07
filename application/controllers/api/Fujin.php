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
    }

    public function create() {
        $result = array();

        $fujinData = array(
            'fj_sender_id' => $_POST['sender_id'],
            'fj_receiver_id' => $_POST['receiver_id'],
            'fj_sj_id' => $_POST['sj_id'],
            'fj_bx_id' => $_POST['bx_id'],
            'fj_jl_id' => $_POST['jl_id'],
            'fj_atime' => $_POST['atime'],
            'fj_stts' => 0,
            'fj_ctime' => time(),
            'fj_utime' => time(),
            'fj_df' => 0
        );

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


    public function getByShangjia() {
        $result = array();

        $ret = $this->fujin->getByShangjia($_POST['sj_id']);
        if (sizeof($ret) > 0) {
            foreach ($ret as $fj) {
                $sender = $this->yonghu->getById($fj['fj_sender_id']);
                $fj['sender'] = $sender[0];
                $receiver = $this->yonghu->getById($fj['fj_receiver_id']);
                $fj['receiver'] = $receiver;
                $data = $this->mjiu->getAll(2, $fj['fj_id']);    //atype, action_id
                $fj['mjiu'] = $data;
            }

            $result['status'] = true;
            $result['data'] = $fj;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }

    public function getByYonghu() {
        $result = array();

        $ret = $this->fujin->getByYonghu($_POST['yh_id'], $_POST['stts']);

        if (sizeof($ret) > 0) {
            foreach ($ret as $fj) {
                $sender = $this->yonghu->getById($fj['fj_sender_id']);
                if(sizeof($sender) > 0)
                    $fj['sender'] = $sender[0];
                else
                    $fj['sender'] = new stdClass();
                $receiver = $this->yonghu->getById($fj['fj_receiver_id']);
                if(sizeof($receiver) > 0)
                    $fj['receiver'] = $receiver[0];
                else
                    $fj['receiver'] = new stdClass();
                $data = $this->mjiu->getAll(2, $fj['fj_id']);    //atype, action_id
                $fj['mjiu'] = $data;
            }

            $result['status'] = true;
            $result['data'] = $fj;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }
}