<?php

/**
 * $Id: Nov 4, 2009 10:40:48 PM navaro $
 * 
 */
 
class dashboard extends Controller
{
	function index()
	{
		redirect('report/indexing');
	}
	
	function indexing()
	{
		$this->load->view('dashboard-indexing',array(
			'username'=>$this->session->userdata('uname'),
			'user_group'=>$this->session->userdata('_login'),
		));
	}
}