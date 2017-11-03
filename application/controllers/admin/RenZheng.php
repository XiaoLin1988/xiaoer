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

        $this->add_script('assets/js/bootstrap/bootstrap-table.min.js', 'head');
        $this->add_script('assets/js/bootstrap/tableExport.js', 'head');
        $this->add_script('assets/js/bootstrap/bootstrap-table-export-m.js', 'head');
        $this->add_script('assets/js/bootstrap/bootstrap-table-export.js', 'head');

        $this->loadView('renzheng');
    }

    public function getRenzhengList() {

        $ret = $this->shangjia->getRenzhengList();
        echo json_encode($ret);

    }

}