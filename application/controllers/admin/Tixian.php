<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/25/2017
 * Time: 12:38 AM
 */
class Tixian extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tixian_model', 'tixian');
        $this->load->model('Mjiu_model', 'mjiu');
        $this->load->model('Jiu_model', 'jiu');
    }

    public function index() {
        $this->add_stylesheet('assets/css/bootstrap-table.min.css');
        $this->add_stylesheet('assets/css/admin.css');

        $this->add_script('assets/js/bootstrap/bootstrap-table.min.js', 'head');
        $this->add_script('assets/js/bootstrap/tableExport.js', 'head');
        $this->add_script('assets/js/bootstrap/bootstrap-table-export-m.js', 'head');
        $this->add_script('assets/js/bootstrap/bootstrap-table-export.js', 'head');

        $data = $this->tixian->getTixianData();

        for($i = 0; $i < sizeof($data); $i ++) {
            $mjiu = $this->mjiu->getAll($data[$i]['trade_type'], $data[$i]['trade_no']);
            $data[$i]['trade_items'] = array();
            foreach($mjiu as $mj) {
                if($mj['mjiu_type'] == 1) {
                    $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                    if(sizeof($jiu) > 0) {
                        $j = array();
                        $j['name'] = $jiu[0]['jiu_name'];
                        $j['price'] = $jiu[0]['jiu_price'];
                        $j['count'] = $mj['mjiu_count'];
                        array_push($data[$i]['trade_items'], $j);
                    }
                }
            }
        }

        $this->mViewData['data'] = $data;
        $this->mViewData['menu_selected'] = 'tixian';
        $this->loadView('tixian');
    }

    public function update() {
        $trade_type = $_POST['trade_type'];
        $trade_no = $_POST['trade_no'];

        $this->tixian->update($trade_type, $trade_no);
    }
}