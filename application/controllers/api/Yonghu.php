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
            'yh_phone' => "",
            'yh_pwd' => "",
            'yh_headimgurl' => $_POST['headimgurl'],
            'yh_balance' => 0,
            'yh_sj_id' => 0,
            'yh_rlat' => 0.0,
            'yh_rlng' => 0.0,
            'yh_raddr' => "",
            'yh_ctime' => time(),
            'yh_utime' => time(),
            'yh_df' => 0
        );

        // check user existing
        $ret = $this->yonghu->getByOpenId($_POST['openId']);
        if (sizeof($ret) == 0) { // new
            $ret1 = $this->yonghu->create($data);
            if (gettype($ret1) == "boolean") {
                $result['status'] = false;
                $result['data'] = "db error";
            } else {
                $ret = $this->yonghu->getByOpenId($_POST['openId']);
                $result['status'] = true;
                $result['data'] = $ret;
            }
        }
        else { // not new, update name, headimgurl

            $data = array(
                'yh_name' => $_POST['name'],
                'yh_headimgurl' => $_POST['headimgurl']
            );

            $ret1 = $this->yonghu->update($data, $ret[0]['yh_id']);
            $ret = $this->yonghu->getByOpenId($_POST['openId']);
            $result['status'] = $ret1;
            $result['data'] = $ret[0];
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

    public function search() {
        $result = array();

        $key = '';
        if (isset($_POST['name'])) {
            $key = $_POST['name'];
        }
        if (isset($_POST['phone'])) {
            $key = $_POST['phone'];
        }

        $ret = $this->yonghu->search($key);

        $result['status'] = true;
        $result['data'] = $ret;

        echo json_encode($ret);
    }
}