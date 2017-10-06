<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/5/2017
 * Time: 1:00 PM
 */
class Api_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function createShangjia($data) {
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
}