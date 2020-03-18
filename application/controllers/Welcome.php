<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */


	public function index()
	{
		$this->load->view('welcome_message');
	}

	/* Test Redis Function :Demo */
	function test_redis(){
		$this->load->library('redis');
		$redis= $this->redis->config();
		$set= $redis->set('Data1','Redizxzxs');
		$get= $redis->get('user_list');
		echo $get;
	}

	/* Get All User Da :Detamo */

	function get_user(){
		$this->load->library('redis');
		$redis= $this->redis->config();

		/* If Data avaialble in Redis*/
		if($redis->get('user_list')){
			echo $redis->get('user_list');
		}else{
			$this->db->select('*');
			$this->db->from('users');
			$result = $this->db->get()->result();
			echo json_encode($result);
	    }

	}

	/* Add New User*/
	function add_user(){

				$this->load->library('redis');
				$redis= $this->redis->config();
				$this->load->model('User_model');
				$jsonArray = json_decode(file_get_contents('php://input'),true); 
 
                $firstname=$jsonArray['firstname'];
				$lastname=$jsonArray['lastname'];
				$email=$jsonArray['email'];
                $phonenumber=$jsonArray['phonenumber'];

				$insertdata=array('firstname'=>$firstname,'lastname'=>$lastname,'email'=>$email,'phone_number'=>$phonenumber);


				$num_rows= $this->User_model->getRecords($email,$phonenumber);
				if($num_rows==0){
					    $this->User_model->saveRecords($insertdata);

					    $this->db->select('*');
						$this->db->from('users');
						$result = $this->db->get()->result();
						$getuserlist=json_encode($result);

						$set= $redis->set('user_list',$getuserlist);

						$response_code=array('status'=>201,'suceess_msg'=>'User has been Added Successfully');
                }else{
		        		$response_code=array('status'=>203,'error_msg'=>'User is Already Exists with this Email / Phone Number');
		        }
				echo json_encode($response_code);


	}
}
