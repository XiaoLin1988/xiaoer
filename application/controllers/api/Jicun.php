<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 10:05 PM
 */
class Jicun extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Jicun_model', 'jicun');
    }

    public function create()
    {
        $result = array();

        $data = array(
            'jc_saver_id' => $_POST['saverId'],
            'jc_contact' => $_POST['contact'],
            'jc_sj_id' => $_POST['shangjiaId'],
            'jc_bx_id' => $_POST['baoxiangId'],
            'jc_jl_id' => $_POST['jingliId'],
            'jc_savingtime' => $_POST['savingtime'],
            'jc_signed' => $_POST['signed'],
            'jc_aprd' => 0,
            'jc_stts' => 1,
            'jc_ctime' => time(),
            'jc_utime' => time(),
            'jc_df' => 0
        );

        $ret = $this->jicun->create($data);
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

        if (isset($_POST['aprd'])) {
            $data['jc_aprd'] = $_POST['aprd'];
        }
        if (isset($_POST['status'])) {
            $data['jc_stts'] = $_POST['status'];
        }
        if (isset($_POST['savingtime'])) {
            $data['jc_savingtime'] = $_POST['savingtime'];
        }
        if (isset($_POST['signed'])) {
            $data['jc_signed'] = $_POST['signed'];
        }
        if (isset($_POST['df'])) {
            $data['jc_df'] = 1;
        }

        $ret = $this->jicun->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }


    public function getShangjiade() {
        $result = array();
        $sj_id = $_POST['shangjiaId'];

        $res = $this->jicun->getShangjiade($sj_id);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function getYonghude() {
        $result = array();
        $yh_id = $_POST['yonghuId'];

        $res = $this->jicun->getYonghude($yh_id);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

}