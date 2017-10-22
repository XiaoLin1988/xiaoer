<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 5:47 PM
 */
class Dingzuo extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dingzuo_model', 'dingzuo');
    }

    public function create() {
        $result = array();

        $data = array(
            'dz_buyer_id' => $_POST['buyerId'],
            'dz_sj_id' => $_POST['shangjiaId'],
            'dz_bx_id' => $_POST['baoxiangId'],
            'dz_atime' => $_POST['atime'],
            'dz_pcount' => $_POST['pcount'],
            'dz_jl_id' => $_POST['jingliId'],
            'dz_stts' => 1,
            'dz_ctime' => time(),
            'dz_utime' => time(),
            'dz_df' => 0
        );

        $ret = $this->dingzuo->create($data);
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

        $data = array();

        if (isset($_POST['status'])) {
            $data['dz_stts'] = $_POST['status'];
        }

        $ret = $this->dingzuo->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }


    public function getShangjiade() {
        $result = array();
        $sj_id = $_POST['shangjiaId'];
        $stts = $_POST['status'];

        $res = $this->dingzuo->getShangjiade($sj_id, $stts);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function getYonghude() {
        $result = array();
        $yh_id = $_POST['yonghuId'];
        $stts = $_POST['status'];

        $res = $this->dingzuo->getYonghude($yh_id, $stts);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function detail($dz_id) {

    }

}