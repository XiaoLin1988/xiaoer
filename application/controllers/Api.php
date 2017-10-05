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
                'mj_oyh_id' => $_POST['user_id']
            );
        }
    }

    public function dingzuo() {

    }

    public function qing() {

    }
}