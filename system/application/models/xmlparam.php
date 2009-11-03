<?php

/**
 * $Id: Sep 16, 2009 7:16:49 PM navaro $
 * 
 */
 
class xmlparam extends Model
{
	var $table = "xmlparams";
	var $limit = 0;
	var $offset = 0;
	
	function xmlparam()
	{
		parent::Model();
		$this->load->database();
	}
	
	function get_rows()
	{
		$query = $this->db->get( $this->table );
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