<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/11/2017
 * Time: 1:15 PM
 */
class Jingli extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Jingli_model', 'jingli');
    }

    public function create() {
        $result = array();
        if (!isset($_POST['name'])) {
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
}