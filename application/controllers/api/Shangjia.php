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
        $this->load->model('Shangjia_model', 'shangjia');
        $this->lang->load('shangjia');
    }

    public function create() {
        $result = array();
        if (!isset($_POST['name'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_name');
        } elseif (!isset($_POST['stime'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_otime');
        } elseif (!isset($_POST['etime'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_etime');
        } elseif (!isset($_POST['addr'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_addr');
        } elseif (!isset($_POST['province'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_province');
        } elseif (!isset($_POST['city'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_city');
        } elseif (!isset($_POST['district'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_district');
        } elseif (!isset($_POST['phone'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_phone');
        } elseif (!isset($_POST['type'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_type');
        } elseif (!isset($_POST['lat'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_lat');
        } elseif (!isset($_POST['lng'])) {
            $result['status'] = false;
            $result['data'] = lang('not_found_lng');
        } else {
            $data = array(
                'sj_name' => $_POST['name'],
                'sj_stime' => $_POST['stime'],
                'sj_etime' => $_POST['etime'],
                'sj_addr' => $_POST['addr'],
                'sj_province' => $_POST['province'],
                'sj_city' => $_POST['city'],
                'sj_district' => $_POST['district'],
                'sj_phone' => $_POST['phone'],
                'sj_type' => $_POST['type'],
                'sj_lat' => $_POST['lat'],
                'sj_lng' => $_POST['lng'],
                'sj_aprd' => 0,
                'sj_aval' => 1,
                'sj_ctime' => time(),
                'sj_utime' => time(),
                'sj_df' => 0
            );

            $ret = $this->shangjia->create($data);
            if (gettype($ret) == "boolean") {
                $result['status'] = false;
                $result['data'] = "Cannot register data to database";
            } else {
                $result['status'] = true;
                $result['data'] = $ret;
            }
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

        $ret = $this->shangjia->nearby($_POST['lat'], $_POST['lng'], $_POST['type']);

        $result['status'] = true;
        $result['data'] = $ret;

        echo json_encode($result);
    }

    public function search() {
        $result = array();

        $data = array();

        if(isset($_POST['name'])) {
            $data['name'] = $_POST['name'];
        } else {
            $data['name'] = '';
        }

        if(isset($_POST['atime'])) {
            $data['atime'] = $_POST['atime'];
        } else {
            $data['atime'] = '0';
        }

        if(isset($_POST['capable'])) {
            $data['capable'] = $_POST['capable'];
        } else {
            $data['capable'] = '0';
        }

        $ret = $this->shangjia->search($data);

        $result['status'] = true;
        $result['data'] = $ret;

        echo json_encode($result);
    }

    public function detail() {
        $result = array();

        if (!isset($_POST['id'])) {
            $result['status'] = false;
            $result['data'] = 'Shangjia id not found';
        } else {
            $ret = $this->shangjia->detail($_POST['id']);

            $result['status'] = true;
            $result['data'] = $ret[0];
        }

        echo json_encode($result);
    }
}