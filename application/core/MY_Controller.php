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

    public function createVerificationCode($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}