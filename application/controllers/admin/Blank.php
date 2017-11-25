<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/25/2017
 * Time: 6:52 AM
 */
class Blank extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->mViewData['menu_selected'] = 'dashboard';
        $this->loadView('blank', 'widget');
    }
}