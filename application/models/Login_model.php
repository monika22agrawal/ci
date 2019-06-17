<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    //Creates directory 
    
    function makedirs($folder='', $mode=DIR_WRITE_MODE, $defaultFolder='upload'){

        if(!@is_dir(FCPATH . $defaultFolder)) {

            mkdir(FCPATH . $defaultFolder, $mode);
        }
        if(!empty($folder)) {

            if(!@is_dir(FCPATH . $defaultFolder . '/' . $folder)){
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode,true);
            }
        } 
    }

    function upload_img($profile_image,$folder)
    { 
        $this->makedirs($folder);

        $allowed_types = "gif|jpg|png|jpeg|JPG|PNG|JPEG|doc|docx|xls|ppt|pdf|txt"; 

        $config = array(
            'upload_path' => FCPATH.'upload/'.$folder,
            'allowed_types' => $allowed_types,
            'overwrite' => false,
            'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            'encrypt_name'=>TRUE ,
            'remove_spaces'=>TRUE
        );

        $this->load->library('upload');
        $this->upload->initialize($config);

        if(!$this->upload->do_upload($profile_image)){
            $error = array('error' => $this->upload->display_errors());
            return $error;

        } else {

            $this->load->library('image_lib');
            $folder_thumb = $folder.'/thumb/';
            $this->makedirs($folder_thumb);

            $width = 100;
            $height = 100;

            $image_data = $this->upload->data(); //upload the image

            $resize['source_image'] = $image_data['full_path'];
            $resize['new_image'] = realpath(APPPATH . '../upload/' . $folder_thumb);
            $resize['maintain_ratio'] = true;
            $resize['width'] = $width;
            $resize['height'] = $height;

            //send resize array to image_lib's  initialize function
            $this->image_lib->initialize($resize);
            $this->image_lib->resize();
            $this->image_lib->clear();

            return $image_data['file_name'];
        }
    }

    // Check email is exist or not
    function checkEmail($email){

        $isExist = $this->db->get_where('users',array('email'=>$email))->row();
        if(!empty($isExist)){
            return false;
        }else{
            return true;
        }
    } //Enf Function

    // Insert user's data in database
    function userRegister($data,$userImgData){

        //inser data in user table
        $this->db->insert(USERS,$data);
        $userId = $this->db->insert_id();

        //check data inserted yes or not
        if(empty($userId)){

            return array('status'=>0,'msg'=>lang('something_wrong'));
        }

        if(!empty($userImgData['image'])){
            $userImgData['user_id'] = $userId;
            $this->db->insert(USERS_IMAGE,$userImgData);
        }     

        if(empty($data['socialId']) && empty($data['socialType'])){
            $this->session_create($userId);
            return array('status'=>1,'msg'=>"NR");

        }elseif(!empty($data['socialId']) && !empty($data['socialType'])){

            $this->session_create($userId); 
            return array('status'=>1,'msg'=>"SR");
        }            
        
    } //Enf Function

    function login($userData) {

        $sql = $this->db->select('userId,password')->where(array('email' =>$userData['email']))->get('users');

        if($sql->num_rows() > 0){
            
            $user = $sql->row(); 

            if(password_verify($userData['password'],$user->password)){

                $this->session_create($user->userId);
                return 'LS'; // Login successfully

            }else{
                return "IP"; // Invalid password
            }           
            
        } else{

            return "IC"; // Invalid credential
        }

    } //End Function

    // Create session for checking user login or not
    function session_create($lastId){

        $sql = $this->db->select('*')->where(array('userId'=>$lastId))->get('users');
        if($sql->num_rows()):
            $user= $sql->row();
            $sessionData = array(
                'email'           => $user->email,
                'fullName'        => $user->fullName,
                'userId'          => $user->userId,
                'front_login'     => true
            );

            $this->session->set_userdata($sessionData);
            return true;
        endif;
        return false;

    }//ENdFunction

    function addNewUser($userData) {
        $isExist = $this->db->select('userId')->where(array('email'=>$userData['email']))->get('users');
        if($isExist->num_rows() > 0){
            return 'AE';
        } else {
            
            $this->db->insert('users', $userData);
            $userId = $this->db->insert_id();
            $this->session_create($userId);
            
            return TRUE;
        }        
    }
}