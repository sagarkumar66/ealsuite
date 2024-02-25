<?php

class General_Model extends CI_Model{

  public function getData($table = '', $filters = [], $select = '*'){
    if(count($filters)>0){
      $this->db->where($filters);
    }
    $this->db->select($select);
    return $this->db->get($table)->result_array();
  }

  public function insertData($table = '', $data){
    $this->db->insert($table, $data);
  }

  public function updateData($table = '', $filters = [], $data = []){
    if(count($filters)>0){
      $this->db->where($filters);
      $this->db->update($table, $data);
    }
  }

  public function deleteData($table = '', $filters = []){
    if(count($filters)>0){
      $this->db->where($filters);
      $this->db->delete($table);
    }
  }

  public function checkDataExists($table = '', $values = [], $filters = []){
    if(count($filters)>0){
      $this->db->where($filters);
    }
    if(count($values)>0){
      $this->db->select(array_keys($values));
      $this->db->group_start();
      foreach ($values as $column => $value) {
        $this->db->or_where($column, $value);
      }
      $this->db->group_end();
    }
    return $this->db->get($table)->result_array();
  }
}


?>
