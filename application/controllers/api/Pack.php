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
        $tmpRes = array();

        $sj_id = $_POST['shangjiaId'];

        $res = $this->pack->get($sj_id);

        foreach ($res as $packItem) {
            $packItem["pk_avatars"] = $this->pack->getImages($packItem["pk_id"], 5); // image type 5  ： pack type
            array_push($tmpRes, $packItem);
        }

        $result['status'] = true;
        $result['data'] = $tmpRes;

        echo json_encode($result);
    }

    public function detail($pk_id) {

    }

    public function delete() {
        $result = array();
        $id = $_POST['id'];

        $ret = $this->pack->delete($id);

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