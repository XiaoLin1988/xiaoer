<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/22/2017
 * Time: 4:15 PM
 */
class Qingke_model extends CI_Model {

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_qingke(".$columns.") VALUES(".$values.")";

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

        $res = $this->db->query("UPDATE tbl_qingke SET ".$sets." WHERE qk_id={$id}");

        return $res;
    }

    public function getByShangjia($sj_id) {
        $query = "SELECT * FROM tbl_qingke WHERE qk_sj_id={$sj_id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getByYonghu($yh_id, $stts) {
        $query = "SELECT * FROM tbl_qingke WHERE (qk_sender_id={$yh_id} OR qk_receiver_id={$yh_id}) AND qk_stts={$stts}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}