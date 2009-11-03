<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * $Id: Sep 14, 2009 2:25:54 PM navaro $
 * 
 */
 
class report extends Controller
{
	function report()
	{
		parent::Controller();
		$this->load->helper(array('url','form'));
		$this->load->model('xmlparam');
		$this->load->library('session');
		$this->load->helper(array('excel','other'));
	}	
	
	function index()
	{
		$xmlfiles = getxmltitle($this->xmlparam);
		$export_file = $inputs = $check = array();
		$last_insert_id = 0;
		
		if(!$this->session->userdata('_login'))
		{
			redirect('/report/login/');
		}else{
			// Upload file
			if(count($_FILES)==1){
				$check = $this->xmlparam->get(
					array('filename', 'username'), 
					array(
						'username'=>$this->session->userdata('uname'),
						'filename'=>$_FILES['xmlfile']['name'],
					)
				);
				if(count($check)==0)
				{
					$config['upload_path'] = FCPATH.'/reports/';
					$config['allowed_types'] = 'xml';
					$config['max_size']	= '100';
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('xmlfile'))
					{
						redirect('/report/index/');
						//print_r($this->upload->display_errors());
					}	
					else
					{
						$upload_data = $this->upload->data();
						$data = handle_xml($upload_data['full_path']);
						
						$store = new stdClass();
						$store->filename = $upload_data['file_name'];
						$store->username = $this->session->userdata('uname');
						$store->datetime = date("Y-m-d H:m:s");
						$store->sql 	 = $data[0]['content']['SQL'];
						if(isset($data[0]['content']['action']))
							$store->action 	 = $data[0]['content']['action'];
						else
							$store->action 	 = '';
						
						$this->xmlparam->insertObj($store);
						$last_insert_id = $this->xmlparam->db->insert_id();
					}
				}				
				if(count($data)>=1){
					/*$this->session->set_userdata(array(
						'sql'=>$data[0]['content']['SQL'],
						'file'=>$_FILES['xmlfile']['name'],
					))*/;
					$inputs = input_define($data[0]['content']['SQL']);
				}
			}// End of Upload file
			
			if($this->input->post('step')=="define")
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
					foreach($params as $k=>$v)
					{
						$fields[] = "PARAM.$k=".$params[$k];
					}
				}
				$this->xmlparam->updateRow($last_insert_id, array('fields'=>implode(';', $fields)));
			}
			if($this->input->post('step')=="export")
			{
				$filename = $this->input->post("filename");//str_replace('.xml', '.xls', );
				$filename = FCPATH."/reports/".$filename;
				$filename=strtolower(fetch_xml_title($filename));
				$filename=str_replace(" ","_", $filename);
				
				$row_id = $this->input->post('row_id');
				$row = $this->xmlparam->get_row($row_id);
				$export_file = export_as($row[0]->sql, $_POST['param'], $filename."_".date("d-m-y_His").".xls");
			}
			
			$action = $this->uri->segment(3);
			$row_id = $this->uri->segment(4);
			$filename = $sql = '';
			$table_field = $info = array();
			
			if($action =="query" && $row_id>=1)
			{
				$row = $this->xmlparam->get_row($row_id);
				$filename = $row[0]->filename;
				if($row[0]->fields!="")
				{
					foreach( explode(';', $row[0]->fields) as $k=>$v)
					{
						$a = explode("=", $v);
						$b = explode(".",$a[0]);
						$param = $this->input->post("param");
						$info[$a[0]] = input_value(explode(".", $a[1]), $a[0], $param[$b[1]]);
					}
				}else{
					redirect("/report/index/update/$row_id");
				}
			}
			if($action =="update" && $row_id>=1)
			{
				$row = $this->xmlparam->get_row($row_id);
				$sql = $row[0]->sql;
				foreach( explode(';', $row[0]->fields) as $k=>$v)
				{
					$a = explode("=", $v);
					$values[] = (isset($a[1]))?$a[1]:"";
				}
				$inputs = input_define($row[0]->sql, $values);
			}
			
			$this->load->view('admin_home_page', array(
				'export_file'=>$export_file,
				'inputs'=>$inputs, #
				'info'=>$info, #
				'xmlfiles'=>$xmlfiles,
				'last_insert_id'=>$last_insert_id,
				'action'=>$action,
				'row_id'=>$row_id,
				'filename'=>$filename,
				'info'=>$info,
				'sql'=>$sql,
				'user_group'=>$this->session->userdata('_login')
			));
		}
	}
	
	function login()
	{
		$this->load->database();
		if(count($_POST)>=1)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$sql = "select * from users where usr_id='$username' and usr_pass='$password'";//echo $sql;
			$query = $this->db->query($sql);
			if( count($query->result())==1 )
			{
				$user = $query->result();
				if($user[0]->usr_group=='ADMIN'){// administrateur
					$user_id_group=1;
				}else if($user[0]->usr_group=='CHROP'){ // charge operation
					$user_id_group=2;
				}
				$this->session->set_userdata(array(
					'_login'=>$user_id_group,
					'uname'=>$user[0]->usr_id
				));
			}
		}
		
		if((!$this->session->userdata('_login')) || $this->session->userdata('_login')==0)
		{
			$this->load->view('login');
		}else{
			redirect('/report/index');
		}
	}
	
	function logout()
	{
		if(!$this->session->userdata('_login'))
		{
			redirect('/report/login/');
		}
		$this->load->library('session');
		$this->session->set_userdata(array('_login'=>''));
		$this->session->unset_userdata('uname');
		redirect('/report/login');
	}
	
	function delete()
	{
		if(!$this->session->userdata('_login'))
		{
			redirect('/report/login/');
		}
		$id = $this->uri->segment(3);
		$this->xmlparam->delete($id);
		$row = $this->xmlparam->get_row($id);
		@unlink(FCPATH.'/reports/'.$row->filename);
		@unlink(FCPATH.'/reports/'.str_replace(".xml", ".xls", $row->filename));
		redirect('/report/index');
	}

	function special()
	{
		if(!$this->session->userdata('_login'))
		{
			redirect('/report/login/');
		}
		$id = $this->uri->segment(3);
		$row = $this->xmlparam->get_row($id);
		$filename = $row[0]->filename;
		if(file_exists(FCPATH."/reports/".$filename))
		{
			$filename=fetch_xml_title(FCPATH."/reports/".$filename);
			$filename=strtolower(str_replace(" ", "_",$filename));
		}
		if($row[0]->fields!="")
		{
			foreach( explode(';', $row[0]->fields) as $k=>$v)
			{
				$a = explode("=", $v);
				$b = explode(".",$a[0]);
				$param = $this->input->post("param");
				$info[$a[0]] = input_value(explode(".", $a[1]), $a[0], $param[$b[1]]);
			}
		}else{
			redirect("/report/index/update/$id");
		}
		$xmlfiles = $this->xmlparam->get_rows();
		if(count($xmlfiles)>=1)
		{
			foreach($xmlfiles as $k=>$v)
			{
				if(file_exists(FCPATH."/reports/".$v->filename))
				{
					$v->title_name = fetch_xml_title(FCPATH."/reports/".$v->filename);		
				}else{
					$v->title_name = "File not found";
				}
			}
		}
		$inputs = input_define($row[0]->sql);
		//$filename = $row[0]->filename;
		$sql = $row[0]->sql;
		$export_file=array();
		if(count($_POST)>=1){
			$sqlquery = ConvertToQuery($row[0]->sql, $_POST['param']);
			$action=$row[0]->action;
			$export_file=call_user_func($action, $sqlquery, $filename."_".date("d-m-y_His").".xls");
		}
		$this->load->view('admin_home_page', array(
			'export_file'=>$export_file,
			'info'=>$info, #
			'xmlfiles'=>$xmlfiles,
			'action'=>'query',
			'row_id'=>$id,
			'filename'=>$filename,
			'sql'=>$sql,
			'user_group'=>$this->session->userdata('_login')
			#'inputs'=>$inputs, #
			#'last_insert_id'=>$last_insert_id,
		));
	}
	
	function script()
	{
		if(!$this->session->userdata('_login'))
		{
			redirect('/report/login/');
		}
		
		$action = $this->uri->segment(3);
		$query_id = $this->uri->segment(4);
		$row = $this->xmlparam->get_row($query_id);
		$inputs = input_define($row[0]->sql);
		$xmlfiles = getxmltitle($this->xmlparam);
		$export_file=array();
		$info = getforms($row[0]);
		$filename = "";
		$sql = $row[0]->sql;
		
		if(count($_POST)>=1){
			if("Delete"==$this->input->post('delete'))
			{
				$sqlquery = "DELETE from %s where %s";
				$table_name = $this->input->post("table_name");
				$params = $this->input->post('param');
				 
				$i=1;
				foreach(explode(';', $row[0]->fields) as $k=>$v)
				{
					$a = explode('=', $v);
					$b = explode('.', $a[1]);
					$_where[] = $b[1]." = '".$params[$i]."'";
					$i++;	
				}
				$sqlquery = sprintf($sqlquery, $table_name, implode(" and ", $_where));
				//$this->db->query($sqlquery);
				echo $sqlquery;	
			}
			if("Update"==$this->input->post('update'))
			{
				$sqlquery = ConvertToQuery($row[0]->sql, $_POST['param']);
			}
			
			
		}
		
		$this->load->view('admin_script_page',array(
		'export_file'=>$export_file,
		'info'=>$info, #
		'xmlfiles'=>$xmlfiles,
		'action'=>$action,
		'row_id'=>$query_id,
		'filename'=>$filename,
		'sql'=>$sql,
		'inputs' => $inputs,		
		'table_name'=>findTablename($row[0]),
		'user_group'=>$this->session->userdata('_login')
		));
	}
}