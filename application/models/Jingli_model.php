<?php

/**
 * Created by PhpStorm.
 * User: macmini
 * Date: 10/21/17
 * Time: 11:23 PM
 */
class Jingli_model extends CI_Model{

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_jingli(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function get($shangjiaId) {
        $res = $this->db->query("select a.jl_id,
                                          a.jl_name,
                                          a.jl_sj_id,
                                          (SELECT b.img_path FROM tbl_image b WHERE a.jl_id = b.img_fid and b.img_type = 4 and b.img_df = 0  ) as avatar
                                        from tbl_jingli a
                                        WHERE a.jl_df=0 and a.jl_sj_id = ".$shangjiaId)->result_array();
        return $res;
    }

    public function delete($id) {

        $query = "Update tbl_jingli SET jl_df = 1  WHERE jl_id = ".$id;

        $res = $this->db->query($query);

        return $res;
    }

}