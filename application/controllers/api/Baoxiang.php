<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/10/2017
 * Time: 11:35 AM
 */
class Baoxiang extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Baoxiang_model', 'baoxiang');
        $this->lang->load('baoxiang');
    }

    public function create() {
        $result = array();

        $data = array(
            'bx_name' => $_POST['name'],
            'bx_sj_id' => $_POST['shangjiaId'],
            'bx_type' => $_POST['type'],
            'bx_capable' => $_POST['capable'],
            'bx_jl_id' => $_POST['jingliId'],
            'bx_stts' => 2,
            'bx_ctime' => time(),
            'bx_utime' => time(),
            'bx_df' => 0
        );
        $ret = $this->baoxiang->create($data);
        if (gettype($ret) == "boolean") {
            $result['status'] = false;
            $result['data'] = "Cannot register data to database";
        } else {
            $result['status'] = true;
            $result['data'] = $ret;
        }

        echo json_encode($result);
    }

    public function update() {
        $result = array();

        $data = array();

        if (isset($_POST['capable'])) {
            $data['bx_capable'] = $_POST['capable'];
        }

        $ret = $this->baoxiang->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }

    public function delete() {
        $result = array();

        $data = array();

        if (isset($_POST['id'])) {
            $data['bx_df'] = 1;
        }

        $ret = $this->baoxiang->update($data, $_POST['id']);

        $result['status'] = $ret;
        $result['data'] = 'success';

        echo json_encode($result);
    }

    public function getAll() {
        $result = array();

        $ret = $this->baoxiang->getAll($_POST['shangjiaId']);

        $result['status'] = true;
        $result['data'] = $ret;

        echo json_encode($result);
    }

    public function search() {
        $result = array();

        $data = array(
            'sj_time' => $_POST['sj_time'],
            'capable' => $_POST['capable'],
            'atime' => $_POST['atime']
        );

        $ret = $this->baoxiang->search($data);

        $result['status'] = true;
        $result['data'] = $ret;

        echo json_encode($result);
    }
}