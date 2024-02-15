<?php

class Invoice_Model extends CI_Model{

  public function getInvoices($params = [], $filters = []){
    $this->db->from('invoice');
    foreach ($params as $table => $param) {
        switch($table){
            case 'invoice':
                $this->db->select($param);
                break;
            case 'customer':
                $this->db->select($param);
                $this->db->join('customer', 'customer.id = invoice.customer_id', 'left');
                break;
        }
    }
    if(count($filters)>0){
      $this->db->where($filters);
    }
    return $this->db->get()->result_array();
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
}


?>
