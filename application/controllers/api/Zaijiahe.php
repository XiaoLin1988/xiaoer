<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/21/2017
 * Time: 11:41 PM
 */
class Zaijiahe extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MaiJiu_model', 'maijiu');
        $this->load->model('Mjiu_model', 'mjiu');
        $this->load->model('Jiu_model', 'jiu');
        $this->load->model('Pack_model', 'pack');
    }

    public function getShangjiade() {
        $result = array();

        $stts = $_POST['status'];

        /* 1: pending 2: approved 3: delivery, 4: received  5: completed 6: canceled */
        //progress list  2, 3, 4
        //
        /*
        if ($stts == 2 or $stts == 3 or $stts == 4) {
            $stts = "2 or mj.mj_stts = 3 or mj.mj_stts = 4 ";
        }
        */

        $data = array();
        $zaijiahe = $this->maijiu->getZaijiaheByShangjia($_POST['shangjiaId'], $stts);
        foreach ($zaijiahe as $zj) {
            $zj['mjiu'] = array();

            $mjiu = $this->mjiu->getAll(1, $zj['mj_id']);
            foreach($mjiu as $mj) {
                if($mj['mjiu_type'] == 1) {
                    $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                    if(sizeof($jiu) > 0) {
                        $jiu = $jiu[0];
                        $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                        $jiu['jiu_count'] = $mj['mjiu_count'];
                        $jiu['jiu_avatars'] = $avatars;

                        array_push($zj['mjiu'], $jiu);
                    }
                } else if ($mj['mjiu_type'] == 2) {
                    $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                    if (sizeof($pack) > 0) {
                        $pack = $pack[0];
                        $pack['jiu_count'] = $mj['mjiu_count'];
                        array_push($zj['mjiu'], $pack);
                    }

                }
            }
            array_push($data, $zj);
        }

        $result['status'] = true;
        $result['data'] = $data;

        echo json_encode($result);

    }

    public function getYonghude() {
        $result = array();

        $stts = $_POST['status'];

        /* 1: pending 2: approved 3: delivery, 4: received  5: completed 6: canceled */
        //progress list  2, 3, 4
        if ($stts == 2 or $stts == 3 or $stts == 4) {
            $stts = "2 or mj.mj_stts = 3 or mj.mj_stts = 4 ";
        }

        $data = array();
        $zaijiahe = $this->maijiu->getZaijiahe($_POST['buyerId'], $stts);
        foreach ($zaijiahe as $zj) {
            $zj['mjiu'] = array();

            $mjiu = $this->mjiu->getAll(1, $zj['mj_id']);
            foreach($mjiu as $mj) {
                if($mj['mjiu_type'] == 1) {
                    $jiu = $this->jiu->detail($mj['mjiu_jiu_id']);
                    if(sizeof($jiu) > 0) {
                        $jiu = $jiu[0];
                        $avatars = $this->jiu->getImages($jiu['jiu_id'], 2);
                        $jiu['jiu_count'] = $mj['mjiu_count'];
                        $jiu['jiu_avatars'] = $avatars;

                        array_push($zj['mjiu'], $jiu);
                    }
                } else if ($mj['mjiu_type'] == 2) {
                    $pack = $this->pack->detail($mj['mjiu_jiu_id']);
                    if (sizeof($pack) > 0) {
                        $pack = $pack[0];
                        $pack['jiu_count'] = $mj['mjiu_count'];
                        array_push($zj['mjiu'], $pack);
                    }

                }
            }
            array_push($data, $zj);
        }

        $result['status'] = true;
        $result['data'] = $data;

        echo json_encode($result);

        /*
        $zaijiahe = $this->maijiu->getZaijiahe($_POST['buyerId']);
        foreach ($zaijiahe as $zj) {
            if ($zj['mjiu_type'] == 1) {
                $jiu = $this->jiu->detail($zj['mjiu_jiu_id']);
                $zj['']
            } else if ($zj['mjiu_type'] == 2) {
                $pack = $this->pack->detail($zj['mjiu_jiu_id']);
            }
        }
        */
    }

}