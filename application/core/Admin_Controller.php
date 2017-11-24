<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/2/2017
 * Time: 8:10 PM
 */
class Admin_Controller extends CI_Controller
{
    protected $mSiteName = '';
    protected $mLoginTime = '';
    protected $mPageTitle = '';
    protected $mViewData;
    protected $mScripts;
    protected $mStyles;

    public function __construct()
    {
        parent::__construct();

        $this->setup();
    }

    private function setup() {
        $config = $this->config->item('app_config');

        $this->mSiteName = empty($config['site_name']) ? '' : $config['site_name'];
        $this->mPageTitle = empty($config['page_title']) ? '' : $config['page_title'];
        $this->mScripts = empty($config['scripts']) ? array() : $config['scripts'];
        $this->mStyles = empty($config['stylesheets']) ? array() : $config['stylesheets'];
    }

    public function loadView($page, $layout = 'widget') {
        $this->mViewData['site_name'] = $this->mSiteName;
        $this->mViewData['page_title'] = $this->mPageTitle;
        $this->mViewData['login_time'] = date('Y:m:d');
        $this->mViewData['stylesheets'] = $this->mStyles;
        $this->mViewData['javascripts'] = $this->mScripts;

        $this->load->view('admin/_partials/header', $this->mViewData);
        $this->load->view('admin/_partials/'.$layout);
        $this->load->view('admin/'.$page, $this->mViewData);
        $this->load->view('admin/_partials/footer', $this->mViewData);
    }

    protected function add_script($files, $position = 'foot', $append = TRUE)
    {
        $files = is_string($files) ? array($files) : $files;
        $position = ($position==='head' || $position==='foot') ? $position : 'foot';

        if ($append)
            $this->mScripts[$position] = array_merge($this->mScripts[$position], $files);
        else
            $this->mScripts[$position] = array_merge($files, $this->mScripts[$position]);
    }

    // Add stylesheet files, either append or prepend to $this->mStylesheets array
    // ($files can be string or string array)
    protected function add_stylesheet($files, $append = TRUE)
    {
        $files = is_string($files) ? array($files) : $files;

        if ($append)
            $this->mStyles = array_merge($this->mStyles, $files);
        else
            $this->mStyles = array_merge($files, $this->mStyles);
    }
}