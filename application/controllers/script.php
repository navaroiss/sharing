<?php
 
/**
 * $Id: Nov 6, 2009 4:35:10 PM navaro $
 *
 */
 
class script extends Controller
{
	function script()
	{
		parent::Controller();
		$this->load->model('xmlparam');
		$this->load->helpers(array(
			'excel', 'export', 'other'
		));
	}
	
	function index()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}
		redirect('script/delete');
	}
	
	function delete()
	{
		if(!$this->auth->session_checking('_login'))
		{
			redirect('/user/login/');
		}else
		{
			if($this->session->userdata('_login')!=1) // # admin
			{
				redirect('/store/showing/');	
			}
		}
		$row_id = $this->uri->segment(3);
		$extra_data = array();
		$params_form = array();
		
		$row = $this->xmlparam->get_row($row_id);
		if(preg_match_all("/PARAM\.(\d+)/", $row[0]->sql, $match))
		{
			if($row[0]->fields!="")
			{
				foreach( explode(';', $row[0]->fields) as $k=>$v)
				{
					$a = explode("=", $v);
					$b = explode(".",$a[0]);
					$param = $this->input->post("param");
					$params_form[$a[0]] = input_value(explode(".", $a[1]), $a[0], $param[$b[1]]);
				}
			}else{
				redirect('store/update/'.$row_id);
			}
		}
		
		if(count($_POST)>=1){
			if("Delete"==$this->input->post('delete'))
			{
				$sqlquery = "DELETE from %s %s";
				$table_name = $this->input->post("table_name");
				$params = $this->input->post('param');
				$i=1;
				//print_r($params);die();
	
				if(isset($_POST['param']))
				{
					$params = $_POST['param'];
					foreach(explode(';', $row[0]->fields) as $k=>$v)
					{
						$a = explode('=', $v);
						$b = explode('.', $a[1]);
						$_where[] = $b[1]." = '".$params[$i]."'";
						$i++;	
					}
					$sqlquery = sprintf($sqlquery, $table_name, " where ".implode(" and ", $_where));
				}else{
					$sqlquery = sprintf($sqlquery, $table_name, "");
				}
				
				//$this->db->query($sqlquery);
				echo $sqlquery;	
			}
			if("Update"==$this->input->post('update'))
			{
				$sqlquery = ConvertToQuery($row[0]->sql, $_POST['param']);
			}
		}
		
		
		$extra_data = array(
			'params' => $params_form,
			'table_name'=>findTablename($row[0]),
		);
		
		$this->load->view('script-delete',
			array_merge(
				array(
						'username'=>$this->session->userdata('uname'),
						'user_group'=>$this->session->userdata('_login')
				), 
				$extra_data
			)
		);
	}
	
}
 