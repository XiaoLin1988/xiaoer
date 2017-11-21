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
                a.*, (SELECT b.img_path FROM tbl_image b WHERE a.sj_id=b.img_fid AND b.img_type = 1 and b.img_df = 0) as avatar
            FROM
                tbl_shangjia a
            WHERE
                a.sj_df = 0 AND a.sj_id={$id} ";
        $res = $this->db->query($query)->result_array();

        return $res;
    }

    public function nearby($lat, $lng, $type) {
        $time = time();

        $data = $this->db->query(
            "SELECT sj.*, round((
				6371 * acos (
				cos ( radians($lat) )
				* cos( radians( sj_lat ) )
				* cos( radians( sj_lng ) - radians($lng) )
				+ sin ( radians($lat) )
				* sin( radians( sj_lat ) )
				)
            ),1) AS distance, (SELECT img_path FROM tbl_image tp WHERE tp.img_type=1 AND tp.img_fid=sj.sj_id) as avatar
            FROM tbl_shangjia sj WHERE sj_df = 0 AND sj_type={$type} AND sj_aprd=1
            HAVING distance < 20
            ORDER BY distance
            LIMIT 0 , 20;")->result_array();

        return $data;
    }

    public function search($data) {
        /*
        $query = "
            SELECT
              sj.*, (SELECT img_path FROM tbl_image tp WHERE tp.img_type=1 AND tp.img_fid=sj.sj_id) as avatar
            FROM
              tbl_shangjia sj, tbl_baoxiang bx
            WHERE
              sj.sj_df = 0 AND bx.bx_sj_id=sj.sj_id
              AND sj.sj_name LIKE '%{$data['name']}%' AND sj_aprd=1
              AND bx.bx_capable >= {$data['capable']}";

        if($data['atime'] != '0') {
            $hour = date('H', $data['atime']);
            $query .= " AND {$hour} >= sj.sj_stime AND {$hour} <= sj.sj_etime";
        }

        $query .= " GROUP BY sj.sj_id";
        */

        $query = "
            SELECT
              sj.*, (SELECT img_path FROM tbl_image tp WHERE tp.img_type=1 AND tp.img_fid=sj.sj_id) as avatar
            FROM
              tbl_shangjia sj
            WHERE
              sj.sj_df = 0
              AND sj.sj_name LIKE '%{$data['name']}%' AND sj_type=1  AND sj_aprd=1";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function searchJiuhang($name) {
        $time = time();

        /*
        $query = "
            SELECT
              sj.*
            FROM
              tbl_shangjia
            WHERE
              sj_df = 0 AND sj_name LIKE '%{$name}%' AND sj_type=0 AND sj_stime<={$time} AND sj_etime>={$time} AND sj_aprd=1 ";
        */

        $query = "
            SELECT
              sj.*, (SELECT img_path FROM tbl_image tp WHERE tp.img_type=1 AND tp.img_fid=sj.sj_id) as avatar
            FROM
              tbl_shangjia sj
            WHERE
              sj.sj_df = 0 AND sj.sj_name LIKE '%{$name}%' AND sj.sj_type=0 AND sj.sj_aprd=1 ";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getRenzhengList() {

        $query = "SELECT sj.* , yh.yh_id, yh.yh_name, yh.yh_headimgurl, (select img.img_path from tbl_image img WHERE img.img_type = 6 AND img.img_df = 0 AND img.img_fid = sj.sj_id) as renzhengImages, (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id=img.img_fid AND img.img_type = 1 and img.img_df = 0) as avatar
                    FROM tbl_shangjia sj RIGHT JOIN tbl_yonghu yh on sj.sj_id = yh.yh_sj_id
                    WHERE sj.sj_df = 0 AND sj.sj_aprd = 0 AND yh.yh_df = 0
                    ORDER BY sj.sj_ctime";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }



}