<?php

/**
 * $Id: Nov 4, 2009 11:04:29 PM navaro $
 * 
 */
 
class store extends Controller
{
	function store()
	{
		parent::Controller();
		$this->load->model('xmlparam');
		$this->load->helpers(array(
			'excel', 'export'
		));
		
	}
	
	function index()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		redirect('store/showing');
	}
	
	function update()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		if($this->session->userdata('_login')!=1)
		{
			redirect('/store/showing/');
		}
		
		$row_id = $this->uri->segment(3);
		
		if(count($_POST)>=1)
		{
			$fields = array();
			if(count($this->input->post('param'))>=1){
				$params = $this->input->post('param');
				$last_insert_id = $this->input->post('row_id');
				if($last_insert_id==0)
				{
					if($this->uri->segment(3)>=1)
					{
						$last_insert_id = $this->uri->segment(3);	
					}
				}
				if(count($params)>=1 && is_array($params))
				{
					foreach($params as $k=>$v)
					{
						$fields[] = "PARAM.$k=".$params[$k];
					}
				}
			}
			$this->xmlparam->updateRow($last_insert_id, array(
				'fields'=>implode(';', $fields),
				'title'=>$_POST['title']
			));
		}
		
		$row_data = $this->xmlparam->get_row($row_id);
		foreach( explode(';', $row_data[0]->fields) as $k=>$v)
		{
			$a = explode("=", $v);
			$values[] = (isset($a[1]))?$a[1]:"";
		}
		
		$inputs = input_define($row_data[0]->sql, $values);
		$extra_data = array(
			'row'=>$row_data[0],
			'row_id'=>$row_id,
			'param_fields'=>$inputs
		);
		
		$this->load->view('store-update',
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
		if($this->session->userdata('_login')!=1)
		{
			redirect('/store/showing/');
		}
		$row_id = $this->uri->segment(3);
		if(is_numeric($row_id))
		{
			$this->xmlparam->delete($row_id);
		}
		redirect('store/showing');
	}
	
	function showing()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		$field = $this->uri->segment(3);
		$sort = $this->uri->segment(4);
		$order_by = array();
		
		if("delete"==$this->input->post('more_act'))
		{
			//print_r($this->input->post('selected_action'));
			$this->xmlparam->delete_more($this->input->post('selected_action'));
		}
		
		if($field != '' and in_array($sort, array('asc','desc')))
		{
			$order_by = array("$field"=>"$sort");
		}
		$extra_data = array(
			'xml_rows'=>$this->xmlparam->get_rows($order_by),
		);
		
		$this->load->view('store-showing',
			array_merge(
				array(
						'username'=>$this->session->userdata('uname'),
						'user_group'=>$this->session->userdata('_login'),
				), 
				$extra_data
			)
		);
	}
	
	function adding()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		if($this->session->userdata('_login')!=1)
		{
			redirect('/store/showing/');
		}
		$extra_data = array();
		
		if(count($_FILES)>=1)
		{
			$config['upload_path'] = FCPATH.'/reports/';
			$config['allowed_types'] = 'xml';
			$config['max_size']	= '100';
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('xmlfile'))
			{
				redirect('/report/index/');//print_r($this->upload->display_errors());
			}	
			else
			{
				$upload_data = $this->upload->data();
				$data = handle_xml($upload_data['full_path']);
				if(count($data)>=1){
					$inputs = input_define($data[0]['content']['SQL']);
				}				
				
				$store = new stdClass();
				$store->filename = $upload_data['file_name'];
				$store->username = $this->session->userdata('uname');
				$store->datetime = date("Y-m-d H:m:s");
				$store->sql 	 = $data[0]['content']['SQL'];
				$store->title 	 = $data[0]['content']['title'];
				if(isset($data[0]['content']['action']))
					$store->action 	 = $data[0]['content']['action'];
				else
					$store->action 	 = '';
				
				$this->xmlparam->insertObj($store);
				$last_insert_id = $this->xmlparam->db->insert_id();
				$extra_data = array(
					'last_insert_id'=>$last_insert_id,
					'inputs'=>$inputs,
					'sql'=>$data[0]['content']['SQL']
				);
			}		
		}
		
		if(count($_POST)>=1)
		{
			$fields = array();
			if(count($this->input->post('param'))>=1){
				$params = $this->input->post('param');
				$last_insert_id = $this->input->post('last_insert_id');
				if($last_insert_id==0)
				{
					if($this->uri->segment(3)>=1)
					{
						$last_insert_id = $this->uri->segment(3);	
					}
				}
				if(count($params)>=1 && is_array($params))
				{
					foreach($params as $k=>$v)
					{
						$fields[] = "PARAM.$k=".$params[$k];
					}
				}
			}
			$extra_fields = array();
			if(isset($_POST['title']) && $_POST['title']!='')
			{
				$extra_fields = array(
					'title' => $_POST['title']
				);
			}
			if("define"==$this->input->post('step'))
			{
				$this->xmlparam->updateRow($last_insert_id, array_merge(array(
					'fields'=>implode(';', $fields),
				)), $extra_fields);
				redirect('store/showing');
			}
		}

		$this->load->view('store-adding',
			array_merge(
				array(
						'username'=>$this->session->userdata('uname'),
						'user_group'=>$this->session->userdata('_login'),
				), 
				$extra_data
			)
		);
	}
	
	function export()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		$extra_data = array();
		$params = array();
		$export_file = array();
		
		$row_id = $this->uri->segment(3);
		$row = $this->xmlparam->get_row($row_id);
		if(preg_match_all("/PARAM\.(\d+)/", $row[0]->sql, $match))
		{
			//print_r($match);
			if($row[0]->fields!="")
			{
				foreach( explode(';', $row[0]->fields) as $k=>$v)
				{
					$a = explode("=", $v);
					$b = explode(".",$a[0]);
					$param = $this->input->post("param");
					$params[$a[0]] = input_value(explode(".", $a[1]), $a[0], $param[$b[1]]);
				}
			}else{
				redirect('store/update/'.$row_id);
			}
		}
		
		if(count($_POST)>=1)
		{
			if($_POST['special_export_function']!='')
			{
				$action = $_POST['special_export_function'];
				$query = $row[0]->sql;
				$filename=str_replace(" ","_", $row[0]->title);
				if(isset($_POST['param']))
				{
					if(count($_POST['param'])>=1)
					{
						foreach($_POST['param'] as $k=>$v)
						{
							$value = (is_numeric($v))?$v:"'".$v."'";
							$query = str_replace("[PARAM.$k]", $value, $query);
						}
					}				
				}
				$export_file = call_user_func($action, $query, $filename."_".date("d-m-y_His"));
			}else{
				$post_param = array();
				if(isset($_POST['param']))
				{
					$post_param = $_POST['param'];	
				}
				$export_file = normal_export($row[0], $post_param);
			}
			
			$report = new stdClass();
			$report->filename = $export_file[0];
			$report->user_id = $this->session->userdata('uname');
			$report->datetime = date("Y-m-d H:m:s");
			$report->xml_id = $row_id;
			$this->load->model('excelfile');
			$this->excelfile->insertObj($report);
		}
		
		$extra_data = array(
			'params'=>$params,
			'special_export_function'=>$row[0]->action,
			'export_file'=>$export_file,
			'export_xml_title'=>$row[0]->title
		);
		
		$this->load->view('store-export',
			array_merge(
				array(
						'username'=>$this->session->userdata('uname'),
						'user_group'=>$this->session->userdata('_login'),
				), 
				$extra_data
			)
		);
	}
}