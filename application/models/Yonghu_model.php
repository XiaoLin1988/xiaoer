<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 3:23 PM
 */
class Yonghu_model extends CI_Model{

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_yonghu(".$columns.") VALUES(".$values.")";

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

        $res = $this->db->query("UPDATE tbl_yonghu SET ".$sets." WHERE yh_id={$id}");

        return $res;
    }

    public function search($key) {
        $query = "SELECT * FROM tbl_yonghu WHERE yh_name LIKE '%{$key}%' OR tbl_yonghu.yh_phone LIKE '%{$key}%'";
        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getById($id) {
        $query = "SELECT * FROM tbl_yonghu WHERE yh_id={$id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getByShangjiaId($sj_id) {
        $query = "SELECT * FROM tbl_yonghu WHERE yh_sj_id={$sj_id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getByOpenId($openId) {
        $query = "SELECT * FROM tbl_yonghu WHERE yh_openId='{$openId}'";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}