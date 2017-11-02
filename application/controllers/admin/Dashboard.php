<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/2/2017
 * Time: 4:47 PM
 */
class Dashboard extends Admin_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->loadView('dashboard');
    }
}