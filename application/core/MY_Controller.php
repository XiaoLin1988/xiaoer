<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/10/2017
 * Time: 1:13 AM
 */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('language');
        $this->data['lang'] = $this->config->item('language');
    }

}