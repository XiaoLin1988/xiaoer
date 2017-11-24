<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/25/2017
 * Time: 5:39 AM
 */
class Login extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->loadView('login', 'full');
    }

    public function doLogin() {
        $password = $_POST['password'];
        $username = $_POST['username'];

        if ($password == 'abc123' and $username == 'wuxiaoer')
            redirect('admin/dashboard');
        else
            redirect('admin/login');
    }
}