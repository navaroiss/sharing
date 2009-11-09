<?php

/**
 * $Id: Nov 4, 2009 9:58:17 PM navaro $
 * 
 */
 
class user extends Controller
{
	function user()
	{
		parent::Controller();
	}
	function index()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}else{
			redirect('/dashboard/indexing/');
		}
	}

	function login()
	{
		if(count($_POST)>=1)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$this->auth->auth_validating($username, $password);
		}
		if(!$this->auth->session_checking('_login'))
		{
			$this->load->view('user-login');
		}else{
			redirect('/dashboard/indexing/');
		}
	}
	
	function logout()
	{
		$this->auth->session_remove('_login');
		redirect('/user/login/');
	}
}