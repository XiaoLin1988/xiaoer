<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 5:49 PM
 */
class Dingzuo_model extends CI_Model{

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_dingzuo(".$columns.") VALUES(".$values.")";

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

        $res = $this->db->query("UPDATE tbl_dingzuo SET ".$sets." WHERE dz_id={$id}");

        return $res;
    }

    public function getShangjiade($sj_id, $stts) {
        $query = "SELECT * FROM tbl_dingzuo WHERE dz_sj_id={$sj_id} and dz_stts = {$stts} ORDER by dz_ctime DESC ";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function getYonghude($yh_id, $stts) {
        $query = "SELECT * FROM tbl_dingzuo WHERE dz_buyer_id={$yh_id} and dz_stts = {$stts} ORDER by dz_ctime DESC ";
        $res = $this->db->query($query)->result_array();

        return $res;
    }
}