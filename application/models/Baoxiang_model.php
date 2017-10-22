<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/10/2017
 * Time: 1:02 AM
 */
class Baoxiang_model extends CI_Model{

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_baoxiang(".$columns.") VALUES(".$values.")";

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

        $res = $this->db->query("UPDATE tbl_baoxiang SET ".$sets." WHERE bx_id={$id}");

        return $res;
    }

    public function getAll($sj_id) {
        $query = "
            SELECT
                *
            FROM
                tbl_baoxiang
            WHERE
                bx_sj_id={$sj_id}";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function search($data) {
        $query = "
            SELECT
              bx.*
            FROM
              tbl_baoxiang bx
            WHERE
              bx.bx_sj_id={$data['sj_id']}
              AND bx.bx_capable >= {$data['capable']}
              AND ((bx.bx_id NOT IN (SELECT dz1.dz_bx_id FROM tbl_dingzuo dz1)) OR (bx.bx_id in (select dz_bx_id from  tbl_dingzuo where bx.bx_id=dz_bx_id AND (dz_stime >= {$data['atime']} OR dz_etime <= {$data['atime']}))))
            GROUP BY bx.bx_id
        ";

        $ret = $this->db->query($query);

        return $ret;
    }

}