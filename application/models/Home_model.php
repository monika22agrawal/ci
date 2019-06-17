<?php
class Home_model extends CI_Model {
	
	function activeBuyer($id) {

		$data = $this->db->select('status')->from('users')->where($id)->get();
		$userStatus = $data->row()->status;
		
		if($userStatus == 1){
			$status = "0";
		}else{
			$status = "1";
		}
		$this->db->where($id)->update('users',array('status'=>$status));
		return  $status;
	}

	function deleteBuyer($id) {

		$this->db->where('id',$id);
		$this->db->delete('users');		
		return true;
	}

	function getAllUser($limit,$start){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->limit($limit,$start);
		$this->db->order_by('userId','desc');
		$req = $this->db->get();
		if($req->num_rows()){
			$res = $req->result();
			return $res;
		}
		return FALSE;
	}
	
	function countAllUser() {
		return $this->db->count_all_results("users");
	}

}
?>
