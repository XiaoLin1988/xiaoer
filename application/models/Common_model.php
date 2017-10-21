<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/22/2017
 * Time: 1:11 AM
 */
class Common_model extends CI_Model {

    public function imageUpload($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_image(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);
        return $res;
    }

}