<?php
class User_Model extends CI_Model 
{
	function saverecords($saverecords)
	{
	    $this->db->insert('users',$saverecords);

	}

	function getRecords($email, $phonenumber){

		 $where = '(email="'.$email.'" or phone_number = "'.$phonenumber.'")';
		 $this->db->from('users');
		 $this->db->where($where);
		 $result = $this->db->get();

		 return $result->num_rows();
         


	}
}
