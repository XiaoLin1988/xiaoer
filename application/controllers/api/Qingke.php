<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/22/2017
 * Time: 4:13 PM
 */
class Qingke extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Qingke_model', 'qingke');
        $this->load->model('Dingzuo_model', 'dingzuo');
        $this->load->model('Mjiu_model', 'mjiu');
    }

    public function create() {
        $result = array();

        $dingzuoData = array(
            'dz_buyer_id' => $_POST['sender_id'],
            'dz_sj_id' => $_POST['sj_id'],
            'dz_bx_id' => $_POST['bx_id'],
            'dz_jl_id' => $_POST['jl_id'],
            'dz_atime' => $_POST['atime'],
            'dz_pcount' => $_POST['pcount'],
            'dz_stts' => 1,
            'dz_ctime' => time(),
            'dz_utime' => time(),
            'dz_df' => 0
        );

        $dz_id = $this->dingzuo->create($dingzuoData);

        $qingkeData = array(
            'qk_sender_id' => $_POST['sender_id'],
            'qk_receiver_id' => $_POST['receiver_id'],
            'qk_dz_id' => $dz_id,
            'qk_stts' => 0,
            'qk_authcode' => $this->createVerificationCode(16),
            'qk_ctime' => time(),
            'qk_utime' => time(),
            'qk_df' => 0
        );

        $qk_id = $this->qingke->create();

    }

    public function update() {

    }

    public function getByShangjia() {

    }

    public function getByYonghu() {

    }
}