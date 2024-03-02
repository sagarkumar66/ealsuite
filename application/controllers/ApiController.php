<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiController extends CI_Controller {

	public function __construct() {
        parent::__construct();   
		$this->load->helper('auth');     
        checkLoggedIn();
    }

	public function list(){
		switch ($_POST['list']) {
			case 'customer':
				$data = $this->listCustomers();
				break;
			case 'invoice':
				$data = $this->listInvoices();
				break;
			default:
				$data = [];
				break;
		}
		echo json_encode(['data' => $data]);die;
	}

	private function listCustomers(){
		$customers = $this->generalModel->getData('customer', ['status'=>1]);
		$data = [];
		foreach ($customers as $key => $customer) {
			$data[$key][] = $customer['name'];
			$data[$key][] = $customer['phone'];
			$data[$key][] = $customer['email'];
			$data[$key][] = $customer['address'];
			$data[$key][] = "<a href='".base_url()."edit/customer/".$customer['id']."'><i class='fa-solid fa-pen-to-square'></i></a>";
		}
		return $data;
	}

	private function listInvoices(){
		$params = []; $filters = [];
		$params['invoice'] = ['invoice.id as invoice_id', 'invoice.date', 'invoice.amount', 'invoice.status'];
		$params['customer'] = ['customer.name'];
		$invoices = $this->invoiceModel->getInvoices($params, $filters);
		$data = [];
		foreach ($invoices as $key => $invoice) {
			$data[$key][] = $invoice['name'];
			$data[$key][] = date('d-m-Y', strtotime($invoice['date']));
			$data[$key][] = $invoice['amount'];
			$data[$key][] = $invoice['status'] == '1'?'Unpaid':($invoice['status'] == '2'?'Paid':($invoice['status'] == '3'?'Cancelled':''));
			if($invoice['status'] == '1'){
				$data[$key][] = "<a href='".base_url()."edit/invoice/".$invoice['invoice_id']."'><i class='fa-solid fa-pen-to-square'></i></a>";
			} else {
				$data[$key][] = "--";
			}
		}
		return $data;
	}

	public function add(){
		switch ($_POST['add']) {
			case 'customer':
				$this->add_customer();
				break;
			case 'invoice':
				$this->add_invoice();
				break;
			default:
				echo json_encode([
					'status' => false,
					'msg'	 => 'Something went wrong'
				]);die;
				break;
		}
	}

	private function add_customer(){
		try{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('phone', 'Phone No', 'trim|numeric|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
			$this->form_validation->set_rules('address', 'Address', 'trim|max_length[100]');

			if($this->form_validation->run() == false){
				$errors = $this->form_validation->error_array();
				throw new Exception(str_replace('"', "", implode("<br>", $errors)));
			} else {
				$values = [
					'phone' 	=> $_POST['phone'],
					'email' 	=> $_POST['email']
				];
				$customers = $this->generalModel->checkDataExists('customer', $values);
				$err = [];
				foreach ($customers as $key => $value) {
					if($value['phone'] != '' && $value['phone'] == $_POST['phone']){
						$err['phone'] = 'Phone No already exists';
					}
					if($value['email'] != '' && $value['email'] == $_POST['email']){
						$err['email'] = 'Email already exists';
					}
				}
				if(count($err)>0){
					throw new Exception(implode("<br>", $err));
				}
				$values['name']	   = $_POST['name'];
				$values['address'] = $_POST['address'];

				$this->generalModel->insertData('customer', $values);
			}
			echo json_encode([
				'status' => true,
				'msg'	 => 'Customer added succesfully'
			]);die;
		} catch (Exception $e){
			echo json_encode([
				'status' => false,
				'msg'	 => $e->getMessage()
			]);die;
		}
	}

	private function add_invoice(){
		try{
			$this->form_validation->set_rules('customer', 'Customer', 'trim|required');
			$this->form_validation->set_rules('date', 'Invoice Date', 'trim|callback_valid_date');
			$this->form_validation->set_rules('amount', 'Invoice Amount', 'trim|numeric');

			if($this->form_validation->run() == false){
				$errors = $this->form_validation->error_array();
				throw new Exception(str_replace('"', "", implode("<br>", $errors)));
			} else {
				if(isset($_POST['amount']) && is_numeric($_POST['amount'] && $_POST['amount'] < 0)) throw new Exception("Amount shouldn't be a negative number");

				$this->generalModel->insertData('invoice', [
					'customer_id' 	=> $_POST['customer'],
					'date' 			=> $_POST['date'],
					'amount' 		=> $_POST['amount'],
				]);
			}
			echo json_encode([
				'status' => true,
				'msg'	 => 'Invoice added succesfully'
			]);die;
		} catch (Exception $e){
			echo json_encode([
				'status' => false,
				'msg'	 => $e->getMessage()
			]);die;
		}
	}

	public function edit(){
		switch ($_POST['edit']) {
			case 'customer':
				$this->edit_customer();
				break;
			case 'invoice':
				$this->edit_invoice();
				break;
			default:
				echo json_encode([
					'status' => false,
					'msg'	 => 'Something went wrong'
				]);die;
				break;
		}
	}

	private function edit_customer(){
		try{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('phone', 'Phone No', 'trim|numeric|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
			$this->form_validation->set_rules('address', 'Address', 'trim|max_length[100]');
			$this->form_validation->set_rules('id', 'Address ID', 'trim|required|numeric');

			if($this->form_validation->run() == false){
				$errors = $this->form_validation->error_array();
				throw new Exception(str_replace('"', "", implode("<br>", $errors)));
			} else {
				$customer = $this->generalModel->getData('customer', ['id' => $_POST['id'], 'status' => '1'], ['id']);
				if(count($customer) == 0) throw new Exception("Invalid Customer");

				$filters = ['id' => $_POST['id']];

				$values = [
					'phone' 	=> $_POST['phone'],
					'email' 	=> $_POST['email']
				];
				$customers = $this->generalModel->checkDataExists('customer', $values,  ['id !=' => $_POST['id']]);
				$err = [];
				foreach ($customers as $key => $value) {
					if($value['phone'] == $_POST['phone']){
						$err['phone'] = 'Phone No already exists';
					}
					if($value['email'] == $_POST['email']){
						$err['email'] = 'Email already exists';
					}
				}
				if(count($err)>0){
					throw new Exception(implode("<br>", $err));
				}
				$values['name']	   = $_POST['name'];
				$values['address'] = $_POST['address'];

				$this->generalModel->updateData('customer', $filters, $values);
			}
			echo json_encode([
				'status' => true,
				'msg'	 => 'Customer updated succesfully'
			]);die;
		} catch (Exception $e){
			echo json_encode([
				'status' => false,
				'msg'	 => $e->getMessage()
			]);die;
		}
	}

	private function edit_invoice(){
		try{
			$this->form_validation->set_rules('customer', 'Customer', 'trim|required');
			$this->form_validation->set_rules('date', 'Invoice Date', 'trim|callback_valid_date');
			$this->form_validation->set_rules('amount', 'Invoice Amount', 'trim|numeric');
			$this->form_validation->set_rules('status', 'Invoice Status', 'trim|required|numeric');
			$this->form_validation->set_rules('id', 'Invoice ID', 'trim|required|numeric');

			if($this->form_validation->run() == false){
				$errors = $this->form_validation->error_array();
				throw new Exception(str_replace('"', "", implode("<br>", $errors)));
			} else {
				if(isset($_POST['amount']) && is_numeric($_POST['amount'] && $_POST['amount'] < 0)) throw new Exception("Amount shouldn't be a negative number");
				
				if(!in_array($_POST['status'], [1, 2, 3])) throw new Exception("Invalid Invoice Status");

				$invoice = $this->generalModel->getData('invoice', ['id' => $_POST['id'], 'status' => '1'], ['id']);
				if(count($invoice) == 0) throw new Exception("Invalid Invoice");

				$filters = [ 'id' => $_POST['id']];
				$updateData = [
					'customer_id' 	=> $_POST['customer'],
					'date' 			=> $_POST['date'],
					'amount' 		=> $_POST['amount'],
					'status'		=> (string)$_POST['status']
				];
				$this->generalModel->updateData('invoice', $filters, $updateData);
			}
			echo json_encode([
				'status' => true,
				'msg'	 => 'Invoice updated succesfully'
			]);die;
		} catch (Exception $e){
			echo json_encode([
				'status' => false,
				'msg'	 => $e->getMessage()
			]);die;
		}
	}

	public function valid_date($date) {
		if (strtotime($date) === false) {
			$this->form_validation->set_message('valid_date', 'The {field} field must be a valid date.');
			return false;
		} else {
			return true;
		}
	}
}

?>
