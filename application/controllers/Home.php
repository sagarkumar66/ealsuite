<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
		$data['page_name'] = '';
		$this->load->view('Home/login.php', $data);
	}

	public function login(){
		try{
			$filters = ['email' => trim($_POST['email']), 'password' => md5(trim($_POST['password']))];
			$select = ['name', 'status'];
			$userData = $this->userModel->getUsers($filters, $select);

			if(count($userData) > 0){
				if($userData[0]['status'] != 1){
					throw new Exception("User is blocked", 1);
				}
				$_SESSION['userName'] = $userData[0]['name'];
			} else {
				throw new Exception("Invalid credetials / User Doesnt exist", 1);
			}
			echo json_encode([
				'status' => true,
				'msg' => 'Login Successful'
			]);die;
		} catch(Exception $e){
			echo json_encode([
				'status' => false,
				'msg' => $e->getMessage()
			]);
		}
	}

	public function logout(){
		session_destroy();
		redirect('Home');
	}
}
