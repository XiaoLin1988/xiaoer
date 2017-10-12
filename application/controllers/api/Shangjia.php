<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/10/2017
 * Time: 12:19 AM
 */
class Shangjia extends MY_Controller {

    public function __construct() {
        parent::__construct();
        //$this->load->model('Shangjia_model', 'shangjia');
        $this->lang->load('shangjia');
    }

    public function create() {
        $result = array();
        if (!isset($_POST['sj_name'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_name');
        } elseif (!isset($_POST['sj_otime'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_otime');
        } elseif (!isset($_POST['sj_etime'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_etime');
        } elseif (!isset($_POST['sj_addr'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_addr');
        } elseif (!isset($_POST['sj_province'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_province');
        } elseif (!isset($_POST['sj_city'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_city');
        } elseif (!isset($_POST['sj_district'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_district');
        } elseif (!isset($_POST['phone'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_phone');
        } elseif (!isset($_POST['type'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_type');
        } elseif (!isset($_POST['sj_lat'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_lat');
        } elseif (!isset($_POST['sj_lng'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_lng');
        } else {
            $result['status'] = true;
            $result['data'] = 1;
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        if (!isset($_POST['sj_id'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_shangjia');
        } else {
            $result['status'] = true;
            $result['data'] = lang('success_update');
        }

        echo json_encode($result);
    }

    public function delete() {
        $result = array();

        if (!isset($_POST['sj_id'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_shangjia');
        } else {
            $result['status'] = true;
            $result['data'] = lang('success_delete');
        }

        echo json_encode($result);
    }

    public function nearby() {
        $result = array();

        if (!isset($_POST['sj_lat'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_lat');
        } elseif (!isset($_POST['sj_lng'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_lng');
        } else {
            $result['status'] = true;

            $sjList = array();
            $sj = new stdClass();
            $sj->sj_id = 1;
            $sj->sj_name = 'shangjia1';
            $sj->sj_otime = 123456987;
            $sj->sj_etime = 123456789;
            $sj->sj_lat = 140.56656;
            $sj->sj_lng = 70.8899;
            $sj->sj_ctime = 129495161;
            $sj->sj_type = 1;
            $sj->sj_addr = 'wenan';
            $sj->sj_province = 'liaoning';
            $sj->sj_city = 'shenyang';
            $sj->sj_district = 'heping';
            $sj->sj_phone = '18715250377';
            $sj->sj_images = array('sj_123456789.png', 'sj_12334535.png', 'sj_34232455.png');

            array_push($sjList, $sj);
            $result['data'] = $sjList;
        }

        echo json_encode($result);
    }

    public function search() {
        $result = array();

        if (!isset($_POST['sj_name'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_name');
        } else {
            $result['status'] = true;

            $sjList = array();
            $sj = new stdClass();
            $sj->sj_id = 1;
            $sj->sj_name = 'shangjia2';
            $sj->sj_otime = 123456987;
            $sj->sj_etime = 123456789;
            $sj->sj_lat = 140.56656;
            $sj->sj_lng = 70.8899;
            $sj->sj_ctime = 129495161;
            $sj->sj_type = 1;
            $sj->sj_addr = 'wenti';
            $sj->sj_province = 'liaoning';
            $sj->sj_city = 'shenyang';
            $sj->sj_district = 'heping';
            $sj->sj_phone = '18715250378';
            $sj->sj_images = array('sj_123456789.png', 'sj_12334535.png', 'sj_34232455.png');

            array_push($sjList, $sj);
            $result['data'] = $sjList;
        }

        echo json_encode($result);
    }

    public function detail($sj_id) {

    }
}