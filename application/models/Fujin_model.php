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

    public function getByShangjia($sj_id) {
        $query = "SELECT * FROM tbl_fujin WHERE qk_sj_id={$sj_id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getByYonghu($yh_id, $stts) {
        $query = "SELECT * FROM tbl_fujin WHERE (fj_sender_id={$yh_id} OR fj_receiver_id={$yh_id}) AND fj_stts={$stts}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}