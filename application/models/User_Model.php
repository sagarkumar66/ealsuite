<?php

class User_Model extends CI_Model{

  public function getUsers($filters = [], $select = '*'){
    if(count($filters)>0){
      $this->db->where($filters);
    }
    $this->db->select($select);
    return $this->db->get('users')->result_array();
  }
}


?>
