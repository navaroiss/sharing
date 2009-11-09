<?php

/**
 * $Id: Nov 4, 2009 10:22:56 PM navaro $
 * 
 */
 
class auth
{
	function auth_validating($username, $password)
	{
		$ci =& get_instance();
		
		$sql = "select * from users where usr_id='$username' and usr_pass='$password'";//echo $sql;
		
		$query = $ci->db->query($sql);
		$user_id_group = 0;
		if( count($query->result())==1 )
		{
			$user = $query->result();
			if($user[0]->usr_group=='ADMIN'){// administrator
				$user_id_group=1;
			}else if($user[0]->usr_group=='CHROP'){ // charge operation
				$user_id_group=2;	
			}
			//echo $user_id_group;
			$ci->session->set_userdata(array(
				'_login'=>$user_id_group,
				'uname'=>$user[0]->usr_id
			));
		}
	}
	
	function session_checking($session_keyname)
	{
		$ci =& get_instance();
		if($ci->session->userdata($session_keyname))
		{
			return true;
		}else
		{
			return false;
		}
	}
	
	function session_remove($session_names=array())
	{
		$ci =& get_instance();
		if(!is_array($session_names)) $ci->session->unset_userdata( $session_names );
	}
}