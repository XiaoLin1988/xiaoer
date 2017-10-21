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
        $file = 'uploads/';

        if (!isset($_FILES['image'])) {
            $result['status'] = false;
            $result['data'] = 'Please select image to upload';
        } else if (!isset($_POST['type']) or !isset($_POST['foreign_id'])) {
            $result['status'] = false;
            $result['data'] = 'Please select your image type';
        } else {
            if ($_POST['type'] == 1) {
                $file .= 'shangjia/';
            } else if ($_POST['type'] == 2) {
                $file .= 'jiu/';
            } else if ($_POST['type'] == 3 or $_POST['type'] == 4) {
                $file .= 'avatar/';
            }

            $file .= time().'.png';
            if(file_exists($file)) {
                chmod($file, 0755);
                unlink($file);
            }

            $upFile = $_FILES['image'];

            $ret = move_uploaded_file($upFile['tmp_name'], $file);
            if ($ret == TRUE) {
                $result['status'] = true;
                $result['data'] = $file;
            } else {
                $result['status'] = false;
                $result['data'] = 'Couldn\'t copy image, try again';
            }
        }

        echo json_encode($result);
    }
}