<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 11/25/2017
 * Time: 1:47 AM
 */
class Tixian_model extends CI_Model
{
    public function getTixianData() {
        $data = array();

        $tixian = new stdClass();
        $tixian->shangjia = 1;
        $tixian->trade_no = 100;
        $tixian->trade_type = 1;

        $tixian->trade_items = array();
        $item1 = new stdClass();
        $item1->name = 'qwert';
        $item1->price = 203;
        $item1->count = 3;
        array_push($tixian->trade_items, $item1);

        $tixian->sender = 44;
        $tixian->amount = 100;

        $fujinQuery = "
                SELECT
                    fj_id as trade_no, fj_sender_id as sender, fj_sj_id as shangjia, 3 as trade_type
                FROM
                    tbl_fujin WHERE fj_stts=7
            ";
        $fujin = $this->db->query($fujinQuery)->result_array();

        $qingkeQuery = "
                SELECT
                    qk_id as trade_no, qk_sender_id as sender, qk_sj_id as shangjia, 2 as trade_type
                FROM
                    tbl_qingke WHERE qk_stts=7
            ";
        $qingke = $this->db->query($qingkeQuery)->result_array();

        $jicunQuery = "
                SELECT
                    jc_id as trade_no, jc_saver_id as sender, jc_sj_id as shangjia, 4 as trade_type
                FROM
                    tbl_jicun WHERE jc_stts=7
            ";
        $jicun = $this->db->query($jicunQuery)->result_array();

        $zaijiQuery = "
                SELECT
                    mj_id as trade_no, mj_buyer_id as sender, mj_sj_id as shangjia, 1 as trade_type
                FROM
                    tbl_maijiu WHERE mj_stts=7
            ";
        $zaijiahe = $this->db->query($zaijiQuery)->result_array();

        $maijiuQuery = "
                SELECT
                    mj_id as trade_no, mj_buyer_id as sender, mj_sj_id as shangjia, 1 as trade_type
                FROM
                    tbl_maijiu WHERE mj_stts=7
            ";
        $maijiu = $this->db->query($maijiuQuery)->result_array();

        foreach ($fujin as $f) {
            array_push($data, $f);
        }

        foreach ($qingke as $q) {
            array_push($data, $q);
        }

        foreach ($jicun as $j) {
            array_push($data, $j);
        }

        foreach ($zaijiahe as $z) {
            array_push($data, $z);
        }

        foreach ($maijiu as $m) {
            array_push($data, $m);
        }

        return $data;
    }

    public function update($trade_type, $trade_no) {
        if ($trade_type == 1) {     // zaijiahe and maijiu
            $query = "UPDATE tbl_maijiu SET mj_stts=5  WHERE mj_id={$trade_no}";
            $res = $this->db->query($query);
            return $res;
        } else if ($trade_type == 2) {  // qingke
            $query = "UPDATE tbl_qingke SET qk_stts=5  WHERE qk_id={$trade_no}";
            $res = $this->db->query($query);
            return $res;
        } else if ($trade_type == 3) {  // fujin
            $query = "UPDATE tbl_fujin SET fj_stts=5  WHERE fj_id={$trade_no}";
            $res = $this->db->query($query);
            return $res;
        } else if ($trade_type == 4) {  // jicun
            $query = "UPDATE tbl_jicun SET jc_stts=5  WHERE jc_id={$trade_no}";
            $res = $this->db->query($query);
            return $res;
        }
    }
}