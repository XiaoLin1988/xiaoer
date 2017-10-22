<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/11/2017
 * Time: 1:16 PM
 */
class Yonghu extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Yonghu_model', 'yonghu');
    }

    public function create() {
        $result = array();

        $data = array(
            'yh_name' => $_POST['name'],
            'yh_openId' => $_POST['openId'],
            'yh_phone' => $_POST['phone'],
            'yh_pwd' => $_POST['password'],
            'yh_balance' => 0,
            'yh_sj_id' => $_POST['shangjiaId'],
            'yh_rlat' => 0.0,
            'yh_rlng' => 0.0,
            'yh_raddr' => "",
            'yh_ctime' => time(),
            'yh_utime' => time(),
            'yh_df' => 0

        );

        $ret = $this->yonghu->create($data);
        if (gettype($ret) == "boolean") {
            $result['status'] = false;
            $result['data'] = "db error";
        } else {
            $result['status'] = true;
            $result['data'] = $ret;
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array(
            'yh_rlat' => $_POST['rlat'],
            'yh_rlng' => $_POST['rlng'],
            'yh_raddr' => $_POST['raddr']
        );

        $ret = $this->yonghu->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }
}