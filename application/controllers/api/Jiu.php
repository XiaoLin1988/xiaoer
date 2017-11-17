<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/11/2017
 * Time: 1:13 PM
 */
class Jiu extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Jiu_model', 'jiu');
    }

    public function create() {
        $result = array();

        $data = array(
            'jiu_sj_id' => $_POST['shangjiaId'],
            'jiu_name' => $_POST['name'],
            'jiu_price' => $_POST['price'],
            'jiu_sale' => $_POST['sale'],
            'jiu_detail' => $_POST['details'],
            'jiu_weight' => $_POST['weight'],
            'jiu_color' => $_POST['color'],
            'jiu_flav' => $_POST['flav'],
            'jiu_company' => $_POST['company'],
            'jiu_aprd' => 0,
            'jiu_ctime' => time(),
            'jiu_utime' => time(),
            'jiu_df' => 0
        );

        $ret = $this->jiu->create($data);
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

        $res = $this->jiu->get($sj_id);

        foreach ($res as $jiuItem) {
            $jiuItem["jiu_avatars"] = $this->jiu->getImages($jiuItem["jiu_id"], 2);
            array_push($tmpRes, $jiuItem);
        }

        $result['status'] = true;
        $result['data'] = $tmpRes;

        echo json_encode($result);
    }

    public function detail($id) {

        // get details information for each jiu
        $res = $this->jiu->detail($id);
        $res[0]["jiu_avatars"] = $this->jiu->getImages($id, 2);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);

    }

    public function delete() {
        $result = array();
        $id = $_POST['id'];

        $ret = $this->jiu->delete($id);

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