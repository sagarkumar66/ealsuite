<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct() {
        parent::__construct();   
		$this->load->helper('auth');     
        checkLoggedIn();
    }

	public function index(){
		$data['page_name'] = 'customer';
		$this->load->view('customer/list_customer.php', $data);
	}

    public function add(){
		$data['page_name'] = 'customer';
		$this->load->view('customer/add_customer', $data);
	}

	public function edit($id = ''){
		$customer = $this->generalModel->getData('customer', ['id' => trim($id)]);
		if(count($customer) == 0){
			redirect('Home');
		}
		$data['customer'] = reset($customer);
		$data['page_name'] = 'customer';
		$this->load->view('customer/edit_customer', $data);
	}

}
?>
