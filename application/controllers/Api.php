<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/5/2017
 * Time: 12:04 PM
 */
class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Api_model', 'api');
    }

    public function createShangjia() {
        $result = array();

        $time = time();

        $shangjia_data = array(
            'sj_name' => $_POST['sj_name'],
            'sj_otime' => time(),
            'sj_etime' => time(),
            'sj_addr' => $_POST['sj_addr'],
            'sj_lat' => $_POST['sj_lat'],
            'sj_lng' => $_POST['sj_lng'],
            'sj_ctime' => $time
        );

        $ret = $this->api->createShangjia($shangjia_data);
        if (gettype($ret) == 'boolean') {
            $result['status'] = 'error';
            $result['data'] = 'Could not save into the database';
        } else {
            $result['status'] = 'success';
            $result['data'] = $ret;
        }

        echo json_encode($result);
    }

    public function createBaoxiang() {

    }

    public function createJiu() {

    }

    public function createYonghu() {

    }

    public function createJicun() {

    }

    public function maijiu() {
        $result = array();

        if (!isset($_POST['sj_id'])) {
            $result['status'] = 'error';
            $result['data'] = 'Please select shangjia';
        } else if(!isset($_POST['user_id'])) {

        } else {
            $maijiu_data = array(
                'mj_sj_id' => $_POST['sj_id'],
                'mj_oyh_id' => $_POST['user_id'],
                'mj_pyh_id' => $_POST['payer_id']
            );
        }
    }

    public function dingzuo() {

    }

    public function qing() {

    }
}