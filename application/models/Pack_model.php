<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 12:32 AM
 */
class Pack_model extends CI_Model{

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_pack(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function get($shangjiaId) {
        $res = $this->db->query("select a.*,
                                  (SELECT b.img_path FROM tbl_image b WHERE a.pk_id = b.img_fid and b.img_type = 5 and b.img_df = 0 ) as avatar
                                  from tbl_pack a
                                  WHERE a.pk_df=0 and a.pk_sj_id =".$shangjiaId)->result_array();
        return $res;
    }

    public function detail($id) {
        $res = $this->db->query("SELECT * FROM tbl_pack WHERE pk_df=0 AND pk_id={$id}")->result_array();

        return $res;
    }

    public function delete($id) {

        $query = "Update tbl_pack SET pk_df = 1  WHERE pk_id = ".$id;

        $res = $this->db->query($query);

        return $res;
    }

}