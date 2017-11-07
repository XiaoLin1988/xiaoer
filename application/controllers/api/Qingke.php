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
        $this->load->model('Dingzuo_model', 'dingzuo');
        $this->load->model('Mjiu_model', 'mjiu');
        $this->load->model('Yonghu_model', 'yonghu');
    }

    public function create() {
        $result = array();

        $qingkeData = array(
            'qk_sender_id' => $_POST['sender_id'],
            'qk_receiver_id' => $_POST['receiver_id'],
            'qk_sj_id' => $_POST['sj_id'],
            'qk_bx_id' => $_POST['bx_id'],
            'qk_jl_id' => $_POST['jl_id'],
            'qk_stts' => 0,
            'qk_authcode' => $this->createVerificationCode(11),
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

            $result['status'] = true;
            $result['data'] = $qk_id;
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

    public function getByShangjia() {
        $result = array();

        $ret = $this->qingke->getByShangjia($_POST['sj_id']);
        if (sizeof($ret) > 0) {
            foreach ($ret as $qk) {
                $sender = $this->yonghu->getById($qk['qk_sender_id']);
                if(sizeof($sender) > 0)
                    $qk['sender'] = $sender[0];
                else
                    $qk['sender'] = new stdClass();
                $receiver = $this->yonghu->getById($qk['qk_receiver_id']);
                if(sizeof($receiver) > 0)
                    $qk['receiver'] = $receiver[0];
                else
                    $qk['sender'] = new stdClass();
                $data = $this->mjiu->getAll(2, $qk['qk_id']);    //atype, action_id
                $qk['mjiu'] = $data;
            }

            $result['status'] = true;
            $result['data'] = $qk;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }

    public function getByYonghu() {
        $result = array();

        $ret = $this->qingke->getByYonghu($_POST['yh_id'], $_POST['stts']);

        if (sizeof($ret) > 0) {
            foreach ($ret as $qk) {
                $sender = $this->yonghu->getById($qk['qk_sender_id']);
                if(sizeof($sender) > 0)
                    $qk['sender'] = $sender[0];
                else
                    $qk['sender'] = new stdClass();
                $receiver = $this->yonghu->getById($qk['qk_receiver_id']);
                if(sizeof($receiver) > 0)
                    $qk['receiver'] = $receiver[0];
                else
                    $qk['sender'] = new stdClass();
                $data = $this->mjiu->getAll(2, $qk['qk_id']);    //atype, action_id
                $qk['mjiu'] = $data;
            }

            $result['status'] = true;
            $result['data'] = $qk;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }
}