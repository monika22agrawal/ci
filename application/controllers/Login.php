<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct(); 
        $this->load->model('Login_model');   
    }

	function index()
	{
		$this->load->view('login');
	}

	function userLogin(){

        $data = array();
        $this->load->library('form_validation');
       
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        
        if ($this->form_validation->run() == FALSE){
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
            $response = array('status' => 0, 'message' => $requireds ); 
            echo json_encode($response);
        } else {
            
            $userData['email'] = $this->input->post('email');
            $userData['password'] = $this->input->post('password');
            
            $isLoggedIn = $this->Login_model->login($userData);

            if(is_string($isLoggedIn) && $isLoggedIn=="LS"){
               
                $response = array('status' => 1, 'message' => 'Logged in successfully', 'url' => base_url('home'));
                               
            } elseif(is_string($isLoggedIn) && $isLoggedIn == "IP"){
                
                $response = array('status' => 0, 'message' => 'Wrong password');

            }elseif(is_string($isLoggedIn) && $isLoggedIn == "IC"){

                $response = array('status' => 0, 'message' => 'Invalid Credential');

            } else {
                
                $response = array('status' => 0, 'message' => 'Somthing went wrong');
            }   
        }
        echo json_encode($response);
    }

    // to check email already register or not
    function checkEmail(){

        $isCheck = $this->Login_model->checkEmail($this->input->get('email'));
        if($isCheck == FALSE){
            echo 'false';
        }else{
            echo 'true';
        }
    }

    function registration()
    {
        $this->load->view('registration');
    }

    function subminRegistration()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('fullName', 'Full name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');        
        if(empty($_FILES['profileImage']['name'])){
            $this->form_validation->set_rules('profileImage','Profile image','required');
        }

        if ($this->form_validation->run() == FALSE){
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation error
            $response = array('status' => 0, 'message' => $requireds ); 
            echo json_encode($response);
        } else {           
           
            $folder = 'profile';
            $profileImage = '';

            if(!empty($_FILES['profileImage']['name'])) {
                $profileImage = $this->Login_model->upload_img('profileImage',$folder);
            }

            if(isset($profileImage) && is_array($profileImage)) {
                
                $response = array('status' => 1, 'message' => $profileImage['error']);
            }else{
                
                $userData['profileImage'] = isset($profileImage) ? $profileImage : '';
                $userData['fullName'] = $this->input->post('fullName');
                $userData['email'] = $this->input->post('email');
                $userData['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

                // Add new user into the database
                $isAdded = $this->Login_model->addNewUser($userData);
             
                if(is_string($isAdded) && $isAdded == 'AE'){

                    $response = array('status' => 0, 'message' => 'Email is already exist.');
                } else {
                    $response = array('status' => 1, 'message' => 'Registration successfully done.', 'url' => base_url('home'));
                }  
            } 
        }
        echo json_encode($response);
    }

}
