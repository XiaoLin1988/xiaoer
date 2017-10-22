<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 10:03 PM
 */
class Jicun_model extends CI_Model
{

    public function create($data)
    {
        $columns = "";
        $values = "";
        foreach ($data as $key => $value) {
            $columns .= $key . ",";
            $values .= "'" . $value . "',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_jicun(" . $columns . ") VALUES(" . $values . ")";

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

        $res = $this->db->query("UPDATE tbl_jicun SET ".$sets." WHERE jc_id={$id}");

        return $res;
    }

    public function getShangjiade($sj_id) {
        $query = "SELECT * FROM tbl_jicun WHERE jc_df = 0 AND jc_sj_id={$sj_id} ORDER by jc_ctime DESC ";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function getYonghude($yh_id) {
        $query = "SELECT * FROM tbl_jicun WHERE jc_df = 0 AND jc_saver_id={$yh_id} ORDER by jc_ctime DESC ";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

}