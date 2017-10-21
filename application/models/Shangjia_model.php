<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/10/2017
 * Time: 1:02 AM
 */
class Shangjia_model extends CI_Model{

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_shangjia(".$columns.") VALUES(".$values.")";

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

        $res = $this->db->query("UPDATE tbl_shangjia SET ".$sets." WHERE sj_id={$id}");

        return $res;
    }

    public function detail($id) {
        $query = "
            SELECT
                a.*, b.img_path
            FROM
                tbl_shangjia a, tbl_image b
            WHERE
                a.sj_id={$id} AND a.sj_id=b.img_fid AND b.img_type=1";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

}