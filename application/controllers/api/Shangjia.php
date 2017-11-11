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
        $this->load->model('Yonghu_model', 'yonghu');
        $this->lang->load('shangjia');

        $this->load->library('Getui', 'getui');
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
                $yh_id = $_POST['yonghuId'];
                $ret1 = $this->yonghu->update(array('yh_sj_id' => $ret), $yh_id);

                if ($ret1) {
                    $result['status'] = true;
                    $result['data'] = $ret;
                } else {
                    $result['status'] = false;
                    $result['data'] = 'no yonghu';
                }
            }
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array();

        if (isset($_POST['name'])) {
            $data['sj_name'] = $_POST['name'];
        }

        if (isset($_POST['stime'])) {
            $data['sj_stime'] = $_POST['stime'];
        }

        if (isset($_POST['etime'])) {
            $data['sj_etime'] = $_POST['etime'];
        }

        if (isset($_POST['addr'])) {
            $data['sj_addr'] = $_POST['addr'];
        }

        if (isset($_POST['province'])) {
            $data['sj_province'] = $_POST['province'];
        }

        if (isset($_POST['city'])) {
            $data['sj_city'] = $_POST['city'];
        }

        if (isset($_POST['district'])) {
            $data['sj_district'] = $_POST['district'];
        }

        if (isset($_POST['phone'])) {
            $data['sj_phone'] = $_POST['phone'];
        }

        if (isset($_POST['lat'])) {
            $data['sj_lng'] = $_POST['lat'];
        }

        if (isset($_POST['lng'])) {
            $data['sj_lng'] = $_POST['lng'];
        }

        if (isset($_POST['aprd'])) {
            $data['sj_aprd'] = $_POST['aprd'];
        }

        if (isset($_POST['aval'])) {
            $data['sj_aval'] = $_POST['aval'];
        }

        $data['sj_utime'] = time();

        $ret = $this->shangjia->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = '';

        echo json_encode($result);
    }

    public function delete() {
        $result = array();

        $data = array(
            'sj_df' => 1
        );

        $ret = $this->shangjia->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = '';

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

        if(isset($_POST['atime']) and $_POST['atime'] != null) {
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

    public function searchJiuhang() {
        $ret = $this->shangjia->searchJiuhang($_POST['name']);

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

            if (sizeof($ret) > 0) {
                $result['status'] = true;
                $result['data'] = $ret[0];
            } else {
                $result['status'] = true;
                $result['data'] = new stdClass();
            }
        }

        echo json_encode($result);
    }

    public function send() {
        //$this->getui->pushMessageToApp();
        $this->getui->pushMessageToSingle();
    }

    public function sendPushtoSingle() {

        $deviceToken = "dbccac9f47ed0a912a89a085c27122d738c53ff164485f04ab57a78d437139da";

        $this->getui->pushMessageToSingleIOS($deviceToken, "Hello, title", "Hello, Information");
    }

    public function sendPushtoMulti() {

        $deviceToken = "dbccac9f47ed0a912a89a085c27122d738c53ff164485f04ab57a78d437139da";
        $sixDeviceToken = "65f0afad45dfa0724174ae3a4e19589eb6c4d6725f1fccff111bcb15a7ac0df3";
        $androidToken = "10a0fc89eb34e6a2b43517afda710632";
        $deviceTokenList = array($deviceToken,$androidToken );

        $this->getui->pushMessageToMulti($deviceTokenList, "Hello, title", "Hello, Information");
    }

}