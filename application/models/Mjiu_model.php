<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 10/22/2017
 * Time: 3:33 PM
 */
class Mjiu_model extends CI_Model {

    public function create($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_mjiu(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function getAll($atype, $action_id) {
        $query = "SELECT * FROM tbl_mjiu WHERE mjiu_atype={$atype} AND mjiu_action_id={$action_id}";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }
}