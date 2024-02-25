<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModuleController extends CI_Controller {

	public function __construct() {
        parent::__construct();   
		$this->load->helper('auth');     
        checkLoggedIn();
    }

    public function add($module = '', $post = 0){
		switch ($module) {
			case 'customer':
				$this->add_customer($post);
				break;
			case 'invoice':
				$this->add_invoice($post);
				break;
			default:
				break;
		}
	}

    public function add_customer($post = 0){
        $data = [
            'page_name' => 'customer',
            'fields'    => [
                [
                    'field' => 'input',
                    'label' => 'Name',
                    'id'    => 'name',
                    'type'  => 'text',
                    'required'  => true,
                    'extra' => [],
                    'backend'   => [] //for backend validation
                ],
                [
                    'field' => 'input',
                    'label' => 'Phone No',
                    'id'    => 'phone',
                    'type'  => 'number',
                    'required'  => false,
                    'extra' => [
                        'maxlength' => 10,
                        'minlength' => 10
                    ],
                    'backend'   => [
                        'checkDuplicate'
                    ]
                ],
                [
                    'field' => 'input',
                    'label' => 'Email',
                    'id'    => 'email',
                    'type'  => 'email',
                    'required'  => false,
                    'extra' => [],
                    'backend'   => [
                        'checkDuplicate'
                    ]
                ],
                [
                    'field' => 'textarea',
                    'label' => 'Address',
                    'id'    => 'address',
                    'type'  => '',
                    'required'  => false,
                    'extra' => [],
                    'backend'   => []
                ],
            ]
        ];
        if($post){
            $this->do_validation_and_insert($data);
        } else {
            $this->load->view('crud_module/add_module', $data);
        }
	}

    public function add_invoice($post = 0){
        $customers = $this->generalModel->getData('customer', ['status' => '1'], ['id as value', 'name as label']);
        $data = [
            'page_name' => 'invoice',
            'fields'    => [
                [
                    'field' => 'select',
                    'options'  => $customers,
                    'label' => 'Customer',
                    'id'    => 'customer_id',
                    'type'  => '',
                    'required'  => true,
                    'extra' => []
                ],
                [
                    'field' => 'input',
                    'label' => 'Invoice Date',
                    'id'    => 'date',
                    'type'  => 'date',
                    'required'  => false,
                    'extra' => []
                ],
                [
                    'field' => 'input',
                    'label' => 'Invoice Amount',
                    'id'    => 'amount',
                    'type'  => 'number',
                    'required'  => false,
                    'extra' => [
                        'min'   => 0
                    ]
                ],
            ]
        ];
		if($post){
            $this->do_validation_and_insert($data);
        } else {
            $this->load->view('crud_module/add_module', $data);
        }
	}

    private function do_validation_and_insert($data) {
        try{
            $types = ['text' => 'alpha_numeric', 'number' => 'numeric', 'email' => 'valid_email'];
            foreach ($data['fields'] as $key => $value) {
                $rules = ['trim'];
                if($value['type'] != '' && array_key_exists($value['type'], $types)){ $rules[] = $types[$value['type']]; }
                if($value['required']){ $rules[] = 'required'; }
                $this->form_validation->set_rules($value['id'], $value['label'], implode('|', $rules));
            }
            if($this->form_validation->run() == false){
                $errors = $this->form_validation->error_array();
                throw new Exception(str_replace('"', "", implode("<br>", $errors)));
            } else {
                $backendValidations = [];
                foreach ($data['fields'] as $field) {
                    if (!empty($field['backend'])) {
                        $backendValidations[] = $field;
                    }
                }

                if(count($backendValidations) > 0){
                    $checkDuplicate = [];
                    foreach ($backendValidations as $field) {
                        if (in_array('checkDuplicate', $field['backend'])) {
                            $checkDuplicate[$field['id']] = $_POST[$field['id']];
                        }
                    } 

                    if(count($checkDuplicate) > 0){
                        $all_data = $this->generalModel->checkDataExists($data['page_name'], $checkDuplicate);//1st param is table_name and 2nd is filter

                        $err = [];
                        foreach ($all_data as $k => $single_data) {
                            foreach ($single_data as $key => $value) {
                                if($single_data[$key] != '' && $single_data[$key] == $checkDuplicate[$key]){
                                    $err[$key] = ucwords($key).' already exists';
                                }
                            }
                        }

                        if(count($err)>0){
                            throw new Exception(implode("<br>", $err));
                        }
                    }
                }

                $this->generalModel->insertData($data['page_name'], $_POST);
            }
            echo json_encode([
                'status' => true,
                'msg'	 => ucwords($data['page_name']).' added succesfully'
            ]);die;
        } catch (Exception $e){
            echo json_encode([
                'status' => false,
                'msg'	 => $e->getMessage()
            ]);die;
        }
    }
}
?>