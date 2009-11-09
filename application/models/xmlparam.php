<?php

/**
 * $Id: Sep 16, 2009 7:16:49 PM navaro $
 * 
 */
 
class xmlparam extends Model
{
	var $table = "pm_params";
	var $limit = 0;
	var $offset = 0;
	
	function xmlparam()
	{
		parent::Model();
		$this->load->database();
	}
	
	function get_rows($order_by = array())
	{
		//print_r($order_by);
		if(count($order_by)>=1)
		{
			foreach($order_by as $k=>$v)
			{	
				$this->db->order_by($k, $v);
			}
		}else{
			$this->db->order_by('datetime', 'desc');
		}
		$query = $this->db->get( $this->table );
		//echo $this->db->last_query();
		return $query->result();
	}
	
	function insertObj($object)
	{
		return $this->db->insert($this->table, $object);
	}
	
	function delete($id)
	{
		return $this->db->query("delete from ".$this->table." where id = $id");
	}
	
	function delete_more($ids)
	{
		return $this->db->query("delete from ".$this->table." where id in (".implode(',', $ids).")");
		//echo "delete from ".$this->table." where id in (".implode(',', $ids).")";
	}
	
	function updateRow($id, $data=array())
	{
		$this->db->where('id', $id);
		$this->db->update($this->table, $data); 		
	}
	
	function get_row($id)
	{
		$query = $this->db->query("select * from ".$this->table." where `id` = ".$id);
		return $query->result();
	}
	
	function get($f=array(), $c=array())
	{
		if(count($f)>=1)
			$this->db->select(implode(',', $f));
		if(count($c)>=1){
			$query = $this->db->get_where($this->table, $c, $this->limit, $this->offset);
		}else{
			$query = $this->db->get($this->table);
		}
		return $query->result();
	}
}