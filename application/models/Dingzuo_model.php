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
        $query = "select dz.*, yh.yh_name, yh.yh_headimgurl,
                (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = dz.dz_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = dz.dz_jl_id) AS jl_name
                from tbl_dingzuo dz RIGHT JOIN tbl_yonghu yh ON dz.dz_buyer_id = yh.yh_id
                where dz.dz_sj_id = {$sj_id} and dz.dz_stts = {$stts} and dz.dz_df = 0 AND yh.yh_df = 0
                ORDER BY dz.dz_ctime desc";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function getYonghude($yh_id, $stts) {
        $query = "select dz.*, sj.sj_name, sj.sj_addr, 
                (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id = img.img_fid AND img.img_type = 1 AND img_df = 0 ) AS sj_avatar,
                (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = dz.dz_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = dz.dz_jl_id) AS jl_name
                from tbl_dingzuo dz RIGHT JOIN tbl_shangjia sj ON dz.dz_sj_id = sj.sj_id
                where dz.dz_buyer_id = {$yh_id} and dz.dz_stts = {$stts} and dz.dz_df = 0 AND sj.sj_df = 0
                ORDER BY dz.dz_ctime desc";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function getDetailsById($id) {
        $query = "SELECT * FROM tbl_dingzuo WHERE dz_id={$id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}