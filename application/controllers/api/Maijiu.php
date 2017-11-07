<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/11/2017
 * Time: 1:17 PM
 */
class Maijiu extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MaiJiu_model', 'maijiu');
        $this->load->model('Mjiu_model', 'mjiu');
    }

    public function create()
    {
        $result = array();

        $data = array(
            'mj_buyer_id' => $_POST['buyerId'],
            'mj_type' => $_POST['type'],
            'mj_sj_id' => $_POST['shangjiaId'],
            'mj_bx_id' => $_POST['baoxiangId'],
            'mj_jl_id' => $_POST['jingliId'],
            'mj_price' => $_POST['price'],
            'mj_aprd' => 0,
            'mj_stts' => 1,
            'mj_ctime' => time(),
            'mj_utime' => time(),
            'mj_df' => 0
        );

        if (isset($_POST['atime'])) {
            $data['mj_atime'] = $_POST['atime'];
        }

        $ret = $this->maijiu->create($data);
        if (gettype($ret) == "boolean") {
            $result['status'] = false;
            $result['data'] = "db error";
        } else {
            $time = time();
            $mjiu = json_decode($_POST['mjiu']);
            foreach($mjiu as $jiu) {
                $mjiuData = array(
                    'mjiu_atype' => 1,
                    'mjiu_action_id' => $ret,
                    'mjiu_type' => $jiu->jiu_type,
                    'mjiu_jiu_id' => $jiu->jiu_id,
                    'mjiu_count' => $jiu->jiu_count,
                    'mjiu_ctime' => $time,
                    'mjiu_utime' => $time,
                    'mjiu_df' => 0
                );

                $this->mjiu->create($mjiuData);
            }

            $result['status'] = true;
            $result['data'] = $ret;
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array();

        if (isset($_POST['aprd'])) {
            $data['mj_aprd'] = $_POST['aprd'];
        }
        if (isset($_POST['status'])) {
            $data['mj_stts'] = $_POST['status'];
        }

        $ret = $this->maijiu->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }

}