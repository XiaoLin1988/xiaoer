<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?></title>

    <?php
        foreach ($stylesheets as $style) {
            $url = starts_with($style, 'http') ? $style : base_url($style);

            echo "<link href='$url' rel='stylesheet' />".PHP_EOL;
        }

        foreach ($javascripts['head'] as $js) {
            $url = starts_with($js, 'http') ? $js : base_url($js);
            echo "<script src='$url'></script>".PHP_EOL;
        }
    ?>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html"><?php echo $site_name; ?></a>
        </div>
        <div style="color:white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"> <?php echo $login_time; ?> &nbsp; <a href="login.html" class="btn btn-danger square-btn-adjust">登出</a> </div>
    </nav>
    <!-- /. NAV TOP  -->
    <nav class="navbar-default navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
                <li class="text-center">
                    <img src="<?php echo base_url('assets/img/find_user.png'); ?>" class="user-image img-responsive"/>
                </li>
                <li>
                    <a class="active-menu"  href="dashboard"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                </li>

                <li>
                    <a  href="renzheng"><i class="fa fa-desktop fa-3x"></i>商家审核</a>
                </li>

                <li>
                    <a  href="user"><i class="fa fa-qrcode fa-3x"></i> Tabs & Panels</a>
                </li>

                <li>
                    <a   href="chart.html"><i class="fa fa-bar-chart-o fa-3x"></i> Morris Charts</a>
                </li>

                <li>
                    <a  href="table.html"><i class="fa fa-table fa-3x"></i> Table Examples</a>
                </li>

                <li>
                    <a  href="form.html"><i class="fa fa-edit fa-3x"></i> Forms </a>
                </li>

                <li>
                    <a   href="login.html"><i class="fa fa-bolt fa-3x"></i> Login</a>
                </li>

                <li>
                    <a   href="registeration.html"><i class="fa fa-laptop fa-3x"></i> Registeration</a>
                </li>

                <li>
                    <a href="#"><i class="fa fa-sitemap fa-3x"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="#">Second Level Link</a>
                        </li>
                        <li>
                            <a href="#">Second Level Link</a>
                        </li>
                        <li>
                            <a href="#">Second Level Link<span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li>
                                    <a href="#">Third Level Link</a>
                                </li>
                                <li>
                                    <a href="#">Third Level Link</a>
                                </li>
                                <li>
                                    <a href="#">Third Level Link</a>
                                </li>

                            </ul>

                        </li>
                    </ul>
                </li>
                <li  >
                    <a  href="blank.html"><i class="fa fa-square-o fa-3x"></i> Blank Page</a>
                </li>
            </ul>

        </div>

    </nav>
    <!-- /. NAV SIDE  -->

    <div id="page-wrapper" >
        <div id="page-inner">