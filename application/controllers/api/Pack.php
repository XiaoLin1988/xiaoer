<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/11/2017
 * Time: 1:14 PM
 */
class Pack extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pack_model', 'pack');
    }

    public function create() {
        $result = array();

        $data = array(
            'pk_sj_id' => $_POST['shangjiaId'],
            'pk_name' => $_POST['name'],
            'pk_price' => $_POST['price'],
            'pk_detail' => $_POST['details'],
            'pk_pcount' => $_POST['pcount'],
            'pk_ctime' => time(),
            'pk_utime' => time(),
            'pk_df' => 0
        );

        $ret = $this->pack->create($data);
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

        $res = $this->pack->get($sj_id);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function detail($pk_id) {

    }

}