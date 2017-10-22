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

    public function nearby($lat, $lng, $type) {
        $time = time();

        $data = $this->db->query(
            "SELECT tbl_shangjia.*, round((
				6371 * acos (
				cos ( radians($lat) )
				* cos( radians( sj_lat ) )
				* cos( radians( sj_lng ) - radians($lng) )
				+ sin ( radians($lat) )
				* sin( radians( sj_lat ) )
				)
            ),1) AS distance
            FROM tbl_shangjia WHERE sj_type={$type} AND sj_stime<={$time} AND sj_etime>={$time}
            HAVING distance < 20
            ORDER BY distance
            LIMIT 0 , 20;")->result_array();

        return $data;
    }

    public function search($data) {
        $query = "
            SELECT
              sj.*
            FROM
              tbl_shangjia sj, tbl_baoxiang bx
            WHERE
              bx.bx_sj_id=sj.sj_id
              AND sj.sj_name LIKE '%{$data['name']}%'
              AND bx.bx_capable >= {$data['capable']}";

        if($data['atime'] != '0') {
            $hour = date('H', $data['atime']);
            $query .= " AND {$hour} >= sj.sj_stime AND {$hour} <= sj.sj_etime";
        }

        $query .= " GROUP BY sj.sj_id";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}