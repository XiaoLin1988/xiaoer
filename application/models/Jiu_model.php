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
        $res = $this->db->query("select a.*,
                                        b.img_path as avatar
                                        from tbl_jiu a, tbl_image b
                                        WHERE a.jiu_id = b.img_fid and b.img_type = 2 and a.jiu_sj_id = ".$shangjiaId)->result_array();
        return $res;
    }

}