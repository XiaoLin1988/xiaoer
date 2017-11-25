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

    public function getByShangjia($sj_id, $stts) {
        $query = "SELECT *,
        (SELECT sj_name FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_name,
              (SELECT sj_addr FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_addr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_headimgurl,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = qk.qk_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = qk.qk_jl_id) AS jl_name
        FROM tbl_qingke qk WHERE qk_sj_id={$sj_id} AND qk_stts={$stts}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getByYonghu($yh_id, $stts) {

        $query = "SELECT * ,
        (SELECT sj_name FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_name,
              (SELECT sj_addr FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_addr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_headimgurl,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = qk.qk_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = qk.qk_jl_id) AS jl_name
              FROM tbl_qingke qk WHERE (qk_sender_id={$yh_id} OR qk_receiver_id={$yh_id}) AND qk_stts={$stts}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getPendingQingkesByYonghuId($yh_id) {

        $query = "SELECT * ,
        (SELECT sj_name FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_name,
              (SELECT sj_addr FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_addr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_headimgurl,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = qk.qk_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = qk.qk_jl_id) AS jl_name
              FROM tbl_qingke qk WHERE (qk_type=1 AND qk_sender_id={$yh_id} AND qk_stts=1) OR ((qk_sender_id={$yh_id} OR qk_receiver_id={$yh_id}) and qk_type=2 AND qk_stts=1)";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getProgressQingkesByYonghuId($yh_id) {

        $query = "SELECT * ,
        (SELECT sj_name FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_name,
              (SELECT sj_addr FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_addr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_headimgurl,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = qk.qk_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = qk.qk_jl_id) AS jl_name
              FROM tbl_qingke qk WHERE (qk_type=1 AND qk_receiver_id={$yh_id} AND qk_stts=2) OR (qk_sender_id={$yh_id} and qk_type=2 AND qk_stts=2)";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getCompletedQingkesByYonghuId($yh_id) {

        $query = "SELECT * ,
                (SELECT sj_name FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_name,
                (SELECT sj_addr FROM tbl_shangjia sj WHERE sj.sj_id=qk.qk_sj_id) AS sj_addr,
                              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_name,
                              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_sender_id) AS sender_headimgurl,
                              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_name,
                              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=qk.qk_receiver_id) AS receiver_headimgurl,
                              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = qk.qk_bx_id) AS bx_name,
                                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = qk.qk_jl_id) AS jl_name
                FROM tbl_qingke qk 
                WHERE (qk_type=1 AND qk_sender_id={$yh_id} AND qk_stts != 1) OR 
                  (qk_type=2 AND qk_receiver_id={$yh_id} AND qk_stts != 1) OR 
                  (qk_sender_id={$yh_id} and qk_type=2 AND (qk_stts != 2 AND qk_stts != 1 )) OR  
                        (qk_receiver_id={$yh_id} and qk_type=1 AND (qk_stts != 2 AND qk_stts != 1 ))";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getDetailsById($id) {
        $query = "SELECT * FROM tbl_qingke WHERE qk_id={$id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

}