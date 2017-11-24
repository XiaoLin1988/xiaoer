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
        $query = "SELECT jc.*, sj.sj_name, sj.sj_addr,
              (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id = img.img_fid AND img.img_type = 1 AND img.img_df = 0 ) AS sj_avatar,
              (SELECT img.img_path FROM tbl_image img WHERE jc.jc_id = img.img_fid AND img.img_type = 8 AND img.img_df = 0 ) AS jc_avatar,
              (SELECT yh_raddr FROM tbl_yonghu yh WHERE yh.yh_id=jc.jc_saver_id) as yh_raddr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=jc.jc_saver_id) as yh_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=jc.jc_saver_id) as yh_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = jc.jc_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = jc.jc_jl_id) AS jl_name
          FROM tbl_jicun jc RIGHT JOIN tbl_shangjia sj ON jc.jc_sj_id =sj.sj_id
          WHERE jc.jc_df = 0 AND jc.jc_sj_id={$sj_id} ORDER by jc.jc_ctime DESC ";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function getYonghude($yh_id) {

        $query = "SELECT jc.*, sj.sj_name, sj.sj_addr,
              (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id = img.img_fid AND img.img_type = 1 AND img.img_df = 0 ) AS sj_avatar,
              (SELECT img.img_path FROM tbl_image img WHERE jc.jc_id = img.img_fid AND img.img_type = 8 AND img.img_df = 0 ) AS jc_avatar,
              (SELECT yh_raddr FROM tbl_yonghu yh WHERE yh.yh_id=jc.jc_saver_id) as yh_raddr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=jc.jc_saver_id) as yh_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=jc.jc_saver_id) as yh_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = jc.jc_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = jc.jc_jl_id) AS jl_name
          FROM tbl_jicun jc RIGHT JOIN tbl_shangjia sj ON jc.jc_sj_id =sj.sj_id
          WHERE jc.jc_df = 0 AND jc.jc_saver_id={$yh_id} ORDER by jc.jc_ctime DESC ";

        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function getDetailsById($id) {
        $query = "SELECT * FROM tbl_jicun WHERE jc_id={$id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}