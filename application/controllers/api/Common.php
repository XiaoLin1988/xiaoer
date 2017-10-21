<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/21/2017
 * Time: 10:41 PM
 */
class Common extends MY_Controller {

    public function imageUpload() {
        $result = array();
        $rootpath = 'uploads/';

        if (!isset($_FILES['image'])) {
            $result['status'] = false;
            $result['data'] = 'Please select image to upload';
        } else if (!isset($_POST['type']) or !isset($_POST['foreign_id'])) {
            $result['status'] = false;
            $result['data'] = 'Please select your image type';
        } else {
            if ($_POST['type'] == 1) {
                $rootpath .= 'shangjia/';
            } else if ($_POST['type'] == 2) {
                $rootpath .= 'jiu/';
            } else if ($_POST['type'] == 3 or $_POST['type'] == 4) {
                $rootpath .= 'avatar/';
            }

            $time = time();

        }

        echo json_encode($result);
    }
}