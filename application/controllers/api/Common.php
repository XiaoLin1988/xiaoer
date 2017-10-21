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