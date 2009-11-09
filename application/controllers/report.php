<?php

/**
 * $Id: Nov 7, 2009 10:14:13 PM navaro $
 * 
 */
 
class report extends Controller
{
	function report()
	{
		parent::Controller();
		$this->load->model('excelfile');
	}
	
	function index()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		redirect('report/showing');
	}
	
	function showing()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		$extra_data = array();
		
		if("delete"==$this->input->post('more_act'))
		{
			$this->excelfile->delete_more($this->input->post('selected_action'));
		}
				
		$extra_data['data_rows'] = $this->excelfile->get_rows();
		
		$this->load->view('report-showing',
			array_merge(
				array(
						'username'=>$this->session->userdata('uname'),
						'user_group'=>$this->session->userdata('_login'),
				), 
				$extra_data
			)
		);
	}

	function remove()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		$id = $this->uri->segment(3);
		$data = $this->excelfile->get_row($id);
		if(!isset($data[0]['id']))
		{
			redirect('/report/showing/');
		}
		if($this->excelfile->delete($id))
		{
			@unlink(FCPATH.'/reports/'.$data[0][filename]);
			redirect('/report/showing/');
		}
	}
}