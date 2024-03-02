<?php

class General_Model extends CI_Model{

  // protected $join = [
  //   'invoice' => ['customer', 'customer.id', 'invoice.customer_id', 'left']
  // ];

  public function getData($table = '', $filters = [], $select = '*'){
    if(count($filters)>0){
      $this->db->where($filters);
    }
    $this->db->select($select);
    return $this->db->get($table)->result_array();
  }

  public function getDataWithJoin($table = '', $join_table = [], $filters = []){
    if(count($filters)>0){
      $this->db->where($filters);
    }
    if(count($join_table) > 0){
      $this->db->from($table);
      foreach ($join_table as $key => $value) {
        switch($key){
          case $table:
            $this->db->select($value);
            break;
          case 'invoice':
            $this->db->select($value);
            $this->db->join('invoice', 'invoice.customer_id = customer.id', 'inner');
            break;
        }
      }
      return $this->db->get()->result_array();
    }
    return [];
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
