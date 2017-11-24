<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/23/2017
 * Time: 1:11 AM
 */
class Fujin extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Fujin_model', 'fujin');
        $this->load->model('Dingzuo_model', 'dingzuo');
        $this->load->model('Mjiu_model', 'mjiu');
        $this->load->model('Yonghu_model', 'yonghu');
        $this->load->model('Jiu_model', 'jiu');
    }

    public function create() {
        $result = array();

        $fujinData = array(
            'fj_sender_id' => $_POST['sender_id'],
            'fj_receiver_id' => $_POST['receiver_id'],
            'fj_sj_id' => $_POST['sj_id'],
            'fj_bx_id' => $_POST['bx_id'],
            'fj_jl_id' => $_POST['jl_id'],
            'fj_stts' => 1,
            'fj_ctime' => time(),
            'fj_utime' => time(),
            'fj_df' => 0
        );

        if (isset($_POST['atime'])) {
            $fujinData['fj_atime'] = $_POST['atime'];
        }

        $fj_id = $this->fujin->create($fujinData);

        if(gettype($fj_id) == "integer") {
            $time = time();
            $mjiu = json_decode($_POST['mjiu']);
            foreach($mjiu as $jiu) {
                $mjiuData = array(
                    'mjiu_atype' => 3,
                    'mjiu_action_id' => $fj_id,
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
            $result['data'] = $fj_id;
        } else {
            $result['status'] = false;
            $result['data'] = 'db error';
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array(
            'qk_utime' => time()
        );
        if (isset($_POST['accept'])) {
            $data['fj_accept'] = $_POST['accept'];
        }
        if (isset($_POST['stts'])) {
            $data['fj_stts'] = $_POST['stts'];
        }
        $this->fujin->update($data, $_POST['id']);

        $result['status'] = true;
        $result['data'] = '';

        echo json_encode($result);
    }


    public function getShangjiade() {
        $result = array();

        $ret = $this->fujin->getByShangjia($_POST['shangjiaId'], $_POST['status']);


        $data = array();
        if (sizeof($ret) > 0) {
            foreach ($ret as $fj) {
                $fj['mjiu'] = array();

                $mjiu = $this->mjiu->getAll(3, $fj['fj_id']);
                foreach($mjiu as $mj) {
                    if($mj['mjiu_type'] == 1) {
                        $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                        if(sizeof($jiu) > 0) {
                            $jiu = $jiu[0];
                            $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                            $jiu['jiu_count'] = $mj['mjiu_count'];
                            $jiu['jiu_avatars'] = $avatars;

                            array_push($fj['mjiu'], $jiu);
                        }
                    } else if ($mj['mjiu_type'] == 2) {
                        $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                        if (sizeof($pack) > 0) {
                            $pack = $pack[0];
                            $pack['jiu_count'] = $mj['mjiu_count'];
                            array_push($fj['mjiu'], $pack);
                        }

                    }
                }
                array_push($data, $fj);
            }

            $result['status'] = true;
            $result['data'] = $data;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }

    public function getYonghude() {
        $result = array();

        $data = array();
        $ret = $this->fujin->getByYonghu($_POST['yonghuId'], $_POST['status']);
        if (sizeof($ret) > 0) {
            foreach ($ret as $fj) {
                $fj['mjiu'] = array();

                $mjiu = $this->mjiu->getAll(3, $fj['fj_id']);
                foreach($mjiu as $mj) {
                    if($mj['mjiu_type'] == 1) {
                        $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                        if(sizeof($jiu) > 0) {
                            $jiu = $jiu[0];
                            $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                            $jiu['jiu_count'] = $mj['mjiu_count'];
                            $jiu['jiu_avatars'] = $avatars;

                            array_push($fj['mjiu'], $jiu);
                        }
                    } else if ($mj['mjiu_type'] == 2) {
                        $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                        if (sizeof($pack) > 0) {
                            $pack = $pack[0];
                            $pack['jiu_count'] = $mj['mjiu_count'];
                            array_push($fj['mjiu'], $pack);
                        }

                    }
                }
                array_push($data, $fj);
            }

            $result['status'] = true;
            $result['data'] = $data;
        } else {
            $result['status'] = true;
            $result['data'] = [];
        }

        echo json_encode($result);
    }
}