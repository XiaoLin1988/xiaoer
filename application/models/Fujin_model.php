<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/23/2017
 * Time: 1:20 AM
 */
class Fujin_model extends CI_Model {
    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_fujin(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function update($data, $id) {
        $sets = "";
        foreach ($data as $key=>$value) {
            $sets .= $key.'="'.$value.'",';
        }

        $sets = rtrim($sets, ",");

        $res = $this->db->query("UPDATE tbl_fujin SET ".$sets." WHERE fj_id={$id}");

        return $res;
    }

    public function getByShangjia($sj_id, $stts) {
        $query =
            "SELECT
              fj.*,
              (SELECT sj_name FROM tbl_shangjia sj WHERE sj.sj_id=fj.fj_sj_id) AS sj_name,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_sender_id) AS sender_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_sender_id) AS sender_headimgurl,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_receiver_id) AS receiver_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_receiver_id) AS receiver_headimgurl
            FROM tbl_fujin fj
            WHERE fj.fj_sj_id={$sj_id} AND fj_stts={$stts}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getByYonghu($yh_id, $stts) {
        $query =
            "SELECT
              fj.*,
              (SELECT sj_name FROM tbl_shangjia sj WHERE sj.sj_id=fj.fj_sj_id) AS sj_name,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_sender_id) AS sender_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_sender_id) AS sender_headimgurl,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_receiver_id) AS receiver_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=fj.fj_receiver_id) AS receiver_headimgurl
            FROM tbl_fujin fj
            WHERE (fj.fj_sender_id={$yh_id} OR fj.fj_receiver_id={$yh_id}) AND fj_stts={$stts}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getDetailsById($id) {
        $query = "SELECT * FROM tbl_fujin WHERE fj_id={$id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}