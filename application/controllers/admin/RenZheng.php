<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/2/2017
 * Time: 10:33 PM
 */
class RenZheng extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Shangjia_model', 'shangjia');
    }

    public function index() {
        $this->add_stylesheet('assets/css/bootstrap-table.min.css');
        $this->add_stylesheet('assets/css/admin.css');

        $this->add_script('assets/js/bootstrap/bootstrap-table.min.js', 'head');
        $this->add_script('assets/js/bootstrap/tableExport.js', 'head');
        $this->add_script('assets/js/bootstrap/bootstrap-table-export-m.js', 'head');
        $this->add_script('assets/js/bootstrap/bootstrap-table-export.js', 'head');

        $data = $this->shangjia->getRenzhengList();
        $this->mViewData['data'] = $data;
        $this->mViewData['menu_selected'] = 'renzheng';
        $this->loadView('renzheng');
    }

    public function getRenzhengList() {

        $ret = $this->shangjia->getRenzhengList();
        echo json_encode($ret);

    }

    public function update() {
        $result = array();

        if (isset($_POST['sj_aprd']) and isset($_POST['shangjiaId'])) {
            $data = array('sj_aprd' => $_POST['sj_aprd']);
            $this->shangjia->update($data, $_POST['shangjiaId']);

            $result['status'] = true;
            $result['data'] = 'success';
        } else {
            $result['status'] = false;
            $result['data'] = 'failed';
        }

        echo json_encode($result);
    }

}