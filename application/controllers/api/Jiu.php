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
        $sj_id = $_POST['shangjiaId'];

        $res = $this->jiu->get($sj_id);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function detail() {

        // get details information for each jiu
        $res = $this->jiu->detail(18);
        $res[0]["jiu_avatars"] = $this->jiu->getImages(18, 2);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);

    }

}