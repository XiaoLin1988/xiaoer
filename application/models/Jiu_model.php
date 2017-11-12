<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/22/17
 * Time: 12:31 AM
 */

class Jiu_Model extends CI_Model{

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_jiu(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function get($shangjiaId) {
        $res = $this->db->query("select * from tbl_jiu WHERE jiu_df=0 and  jiu_sj_id = ".$shangjiaId)->result_array();
        return $res;
    }

    public function detail($id) {
        $res = $this->db->query("select * from tbl_jiu WHERE jiu_df=0 and jiu_id = ".$id)->result_array();
        return $res;
    }

    public function getImages($id, $type) {
        $res = $this->db->query("select img_path from tbl_image WHERE img_df=0 and img_type = ".$type." and img_fid = ".$id." order by img_ctime")->result_array();
        return $res;
    }

}