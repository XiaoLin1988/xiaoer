<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/21/2017
 * Time: 10:41 PM
 */
class Common extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model', 'common');
    }

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
            } else if ($_POST['type'] == 2 or $_POST['type'] == 5) {
                $file .= 'jiu/';
            } else if ($_POST['type'] == 3 or $_POST['type'] == 4) {
                $file .= 'avatar/';
            } else if ($_POST['type'] == 6) {
                $file .= 'renzheng/';
            } else if ($_POST['type'] == 7) { // card image for withdrawal
                $file .= 'card/';
            } else if ($_POST['type'] == 8) { // jicundan
                $file .= 'jicundan/';
            }

            $file .= time().'.png';
            if(file_exists($file)) {
                chmod($file, 0755);
                unlink($file);
            }

            $upFile = $_FILES['image'];

            $ret = move_uploaded_file($upFile['tmp_name'], $file);
            if ($ret == TRUE) {

                $data = array(
                    'img_path' => $file,
                    'img_type' => $_POST['type'],
                    'img_fid' => $_POST['foreign_id'],
                    'img_ctime' => time(),
                    'img_utime' => time(),
                    'img_df' => 0
                );

                $ret = $this->common->imageUpload($data);
                if ($ret == TRUE) {
                    $result['status'] = true;
                    $result['data'] = $file;
                } else {
                    $result['status'] = false;
                    $result['data'] = 'Couldn\'t save image to database, try again';
                }
            } else {
                $result['status'] = false;
                $result['data'] = 'Couldn\'t copy image, try again';
            }
        }

        echo json_encode($result);
    }

    public function imageMultiUpload() {
        $result = array();
        $data = array();
        $root = 'uploads/';

        if (!isset($_FILES['images'])) {
            $result['status'] = false;
            $result['data'] = 'Please select image to upload';
        } else if (!isset($_POST['type']) or !isset($_POST['foreign_id'])) {
            $result['status'] = false;
            $result['data'] = 'Please select your image type';
        } else {
            if ($_POST['type'] == 1) {
                $root .= 'shangjia/';
            } else if ($_POST['type'] == 2 or $_POST['type'] == 5) {
                $root .= 'jiu/';
            } else if ($_POST['type'] == 3 or $_POST['type'] == 4) {
                $root .= 'avatar/';
            } else if ($_POST['type'] == 6) {
                $root .= 'renzheng/';
            } else if ($_POST['type'] == 7) { // card image for withdrawal
                $file .= 'card/';
            } else if ($_POST['type'] == 8) { // jicundan
                $file .= 'jicundan/';
            }

            $upFiles = $_FILES['images'];

            for ($i = 0; $i < sizeof($upFiles['tmp_name']); $i++) {

                $file = $root . $this->createVerificationCode(20) . ".png";

                if (file_exists($file)) {
                    chmod($file, 0755);
                    unlink($file);
                }

                $ret = move_uploaded_file($upFiles['tmp_name'][$i], $file);
                if ($ret == TRUE) {
                    $key_value = array(
                        'img_path' => $file,
                        'img_type' => $_POST['type'],
                        'img_fid' => $_POST['foreign_id'],
                        'img_ctime' => time(),
                        'img_utime' => time(),
                        'img_df' => 0
                    );
                    $ret = $this->common->imageUpload($key_value);

                    if ($ret == TRUE) {
                        $d = array('test' => $file);
                        //array_push($data, $d);
                        array_push($data, $file);
                    }
                }
            }

            $result['status'] = true;
            $result['data'] = $data;
        }

        echo json_encode($result);

    }

    public function imageUpdate() {
        $result = array();

        $file = 'uploads/';

        if (!isset($_FILES['image'])) {
            $result['status'] = false;
            $result['data'] = 'Please select image to upload';
        } else if (!isset($_POST['type']) or !isset($_POST['foreign_id'])) {
            $result['status'] = false;
            $result['data'] = 'Please select your image type';
        } else {

            // get fid, type from request
            $fid = $_POST['foreign_id'];
            $type = $_POST['type'];

            // delete already existing images
            $ret = $this->common->imageDelete($fid, $type);

            if ($_POST['type'] == 1) {
                $file .= 'shangjia/';
            } else if ($_POST['type'] == 2 or $_POST['type'] == 5) {
                $file .= 'jiu/';
            } else if ($_POST['type'] == 3 or $_POST['type'] == 4) {
                $file .= 'avatar/';
            } else if ($_POST['type'] == 7) { // card image for withdrawal
                $file .= 'card/';
            } else if ($_POST['type'] == 8) { // jicundan
                $file .= 'jicundan/';
            }

            $file .= time().'.png';
            if(file_exists($file)) {
                chmod($file, 0755);
                unlink($file);
            }

            $upFile = $_FILES['image'];

            $ret = move_uploaded_file($upFile['tmp_name'], $file);
            if ($ret == TRUE) {

                $data = array(
                    'img_path' => $file,
                    'img_type' => $_POST['type'],
                    'img_fid' => $_POST['foreign_id'],
                    'img_ctime' => time(),
                    'img_utime' => time(),
                    'img_df' => 0
                );

                $ret = $this->common->imageUpload($data);
                if ($ret == TRUE) {
                    $result['status'] = true;
                    $result['data'] = $file;
                } else {
                    $result['status'] = false;
                    $result['data'] = 'Couldn\'t save image to database, try again';
                }
            } else {
                $result['status'] = false;
                $result['data'] = 'Couldn\'t copy image, try again';
            }
        }

        echo json_encode($result);
    }
}