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
    }

    public function create() {
        $result = array();

        $qingkeData = array(
            'qk_sender_id' => $_POST['sender_id'],
            'qk_receiver_id' => $_POST['receiver_id'],
            'qk_sj_id' => $_POST['sj_id'],
            'qk_bx_id' => $_POST['bx_id'],
            'qk_jl_id' => $_POST['jl_id'],
            'qk_atime' => $_POST['atime'],
            'qk_stts' => 0,
            'qk_authcode' => $this->createVerificationCode(16),
            'qk_ctime' => time(),
            'qk_utime' => time(),
            'qk_df' => 0
        );

        $qk_id = $this->qingke->create($qingkeData);

        if(gettype($qk_id) == "integer") {
            $time = time();
            foreach($_POST['mjiu'] as $jiu) {
                $mjiuData = array(
                    'mjiu_atype' => 2,
                    'mjiu_action_id' => $qk_id,
                    'mjiu_type' => $jiu['type'],
                    'mjiu_jiu_id' => $jiu['id'],
                    'mjiu_count' => $jiu['count'],
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

    }

    public function getByShangjia() {
        $result = array();

        $ret = $this->qingke->getByShangjia($_POST['sj_id']);
        if (sizeof($ret) > 0) {
            $qingke = $ret[0];

            $data = $this->mjiu->getAll(2, $qingke['qk_id']);    //atype, action_id
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }

    public function getByYonghu() {

    }
}