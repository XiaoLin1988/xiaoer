<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 6:36 PM
 */
class MaiJiu_model extends CI_Model
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

        $query = "INSERT INTO tbl_maijiu(" . $columns . ") VALUES(" . $values . ")";

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

        $res = $this->db->query("UPDATE tbl_maijiu SET ".$sets." WHERE mj_id={$id}");

        return $res;
    }

    public function getMaijiuByYonghu($buyerId, $status) {
        $res = $this->db->query("SELECT mj.*, sj.sj_name, sj.sj_addr,
              (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id = img.img_fid AND img.img_type = 1 AND img.img_df = 0 ) AS sj_avatar,
              (SELECT yh_raddr FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_raddr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = mj.mj_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = mj.mj_jl_id) AS jl_name
          FROM tbl_maijiu mj RIGHT JOIN tbl_shangjia sj ON sj.sj_id=mj.mj_sj_id 
          WHERE  mj.mj_df=0 AND mj.mj_buyer_id={$buyerId} AND mj.mj_type=2 AND (mj.mj_stts={$status})")->result_array();

        return $res;
    }

    public function getMaijiuByShangjia($shangjiaId, $status) {
        $res = $this->db->query("SELECT mj.*, sj.sj_name, sj.sj_addr,
              (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id = img.img_fid AND img.img_type = 1 AND img.img_df = 0 ) AS sj_avatar,
              (SELECT yh_raddr FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_raddr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_headimgurl,
              (SELECT bx.bx_name FROM tbl_baoxiang bx WHERE bx.bx_id = mj.mj_bx_id) AS bx_name,
                (SELECT jl.jl_name FROM tbl_jingli jl WHERE jl.jl_id = mj.mj_jl_id) AS jl_name
          FROM tbl_maijiu mj RIGHT JOIN tbl_shangjia sj ON sj.sj_id=mj.mj_sj_id 
          WHERE  mj.mj_df=0 AND mj.mj_sj_id={$shangjiaId} AND mj.mj_type=2 AND (mj.mj_stts={$status})")->result_array();

        return $res;
    }

    public function getZaijiaheByYonghu($buyerId, $status) {
        $res = $this->db->query("SELECT mj.*, sj.sj_name, sj.sj_addr,
              (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id = img.img_fid AND img.img_type = 1 AND img.img_df = 0 ) AS sj_avatar,
              (SELECT yh_raddr FROM tbl_yonghu yh WHERE yh.yh_id={$buyerId}) as yh_raddr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id={$buyerId}) as yh_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id={$buyerId}) as yh_headimgurl
          FROM tbl_maijiu mj RIGHT JOIN tbl_shangjia sj ON sj.sj_id=mj.mj_sj_id 
          WHERE  mj.mj_df=0 AND mj.mj_buyer_id={$buyerId} AND mj.mj_stts={$status} AND mj.mj_type=1")->result_array();

        return $res;
    }

    public function getZaijiaheByShangjia($shangjiaId, $status) {
        $res = $this->db->query("SELECT mj.*, sj.sj_name, sj.sj_addr,
              (SELECT img.img_path FROM tbl_image img WHERE sj.sj_id = img.img_fid AND img.img_type = 1 AND img.img_df = 0 ) AS sj_avatar,
              (SELECT yh_raddr FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_raddr,
              (SELECT yh_name FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_name,
              (SELECT yh_headimgurl FROM tbl_yonghu yh WHERE yh.yh_id=mj.mj_buyer_id) as yh_headimgurl
          FROM tbl_maijiu mj RIGHT JOIN tbl_shangjia sj ON sj.sj_id=mj.mj_sj_id 
          WHERE  mj.mj_df=0 AND sj.sj_type = 0 AND mj.mj_sj_id={$shangjiaId} AND mj.mj_type=1 AND (mj.mj_stts={$status})")->result_array();

        return $res;
    }

    public function getDetailsById($id) {
        $query = "SELECT * FROM tbl_maijiu WHERE mj_id={$id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

}