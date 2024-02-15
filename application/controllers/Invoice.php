<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

	public function __construct() {
        parent::__construct();   
		$this->load->helper('auth');     
        checkLoggedIn();
    }

	public function index(){
		$data['page_name'] = 'invoice';
		$this->load->view('invoice/list_invoice.php', $data);
	}

	public function add(){
		$customers = $this->generalModel->getData('customer', ['status' => '1'], ['id', 'name']);
		$data['customer']  = $customers;
		$data['page_name'] = 'invoice';
		$this->load->view('invoice/add_invoice', $data);
	}

	public function edit($id = ''){
		$all_customers = $this->generalModel->getData('customer', ['status' => '1'], ['id', 'name']);
		$data['customers'] = $all_customers;

		$invoice = $this->generalModel->getData('invoice', ['id' => trim($id), 'status' => '1']);
		if(count($invoice) == 0){
			redirect('Home');
		}
		$data['invoice']   = reset($invoice);
		$data['page_name'] = 'invoice';
		$this->load->view('invoice/edit_invoice', $data);
	}
}
?>
