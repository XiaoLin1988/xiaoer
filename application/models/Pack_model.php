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
        $res = $this->db->query("select * from tbl_pack WHERE pk_df=0 and  pk_sj_id = ".$shangjiaId)->result_array();
        return $res;
    }

    public function getImages($id, $type) {
        $res = $this->db->query("select img_path from tbl_image WHERE img_df=0 and img_type = ".$type." and img_fid = ".$id." order by img_ctime")->result_array();
        return $res;
    }

    public function delete($id) {

        $query = "Update tbl_pack SET pk_df = 1  WHERE pk_id = ".$id;

        $res = $this->db->query($query);

        return $res;
    }

}