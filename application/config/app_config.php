<?php
/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/2/2017
 * Time: 10:04 PM
 */

$config['app_config'] = array(

    // Site name
    'site_name' => '小二上酒',

    // Default page title
    'page_title' => '小二上酒',

    // Default scripts to embed at page head or end
    'scripts' => array(
        'head'	=> array(
            'assets/js/jquery.min.js',
            'assets/js/jquery-ui.js',
            'assets/js/bootstrap.min.js',
            'assets/js/swal/sweet-alert.js'
        ),
        'foot'	=> array(
            'assets/js/jquery.metisMenu.js',
            'assets/js/morris/raphael-2.1.0.min.js',
            'assets/js/morris/morris.js',
            'assets/js/custom.js'
        ),
    ),

    // Default stylesheets to embed at page head
    'stylesheets' => array(
        'assets/css/bootstrap.css',
        'assets/css/font-awesome.css',
        'assets/js/morris/morris-0.4.3.min.css',
        'assets/css/custom.css',
        'assets/css/swal/sweet-alert.css'
    )
);