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

        $data = array(
            'jl_name' => $_POST['name'],
            'jl_sj_id' => $_POST['shangjiaId'],
            'jl_ctime' => time(),
            'jl_utime' => time(),
            'jl_df' => 0
        );

        $ret = $this->jingli->create($data);
        if (gettype($ret) == "boolean") {
            $result['status'] = false;
            $result['data'] = "db error";
        } else {
            $result['status'] = true;
            $result['data'] = $ret;
        }

        echo json_encode($result);
    }

    public function get() {
        $result = array();
        $sj_id = $_POST['shangjiaId'];

        $res = $this->jingli->get($sj_id);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function delete() {
        $result = array();
        $id = $_POST['id'];

        $ret = $this->jingli->delete($id);

        if ($ret) {
            $result['status'] = true;
            $result['data'] = $ret;
        } else {
            $result['status'] = false;
            $result['data'] = "db error";
        }

        echo json_encode($result);
    }

}