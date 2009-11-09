<?php

/**
 * $Id: Nov 7, 2009 10:11:41 PM navaro $
 * 
 */
 
class excelfile extends Model
{
	var $table = "pm_reports";
	var $table_join = "pm_params";
	var $table_user = 'users';
	var $limit = 0;
	var $offset = 0;
	
	function report()
	{
		parent::Model();
		$this->load->database();
	}
	
	function get_rows()
	{
		$query = $this->db->query("	
					select a.*, b.title, u.usr_name from ".$this->table." a 
						inner join ".$this->table_join." b on b.id = a.xml_id
						inner join ".$this->table_user." u on a.user_id = u.usr_id
					order by a.datetime desc
					"); 
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
		$query = $this->db->query("
			select a.*, b.title, u.usr_name from ".$this->table." a 
				inner join ".$this->table_join." b on a.id = b.xml_id 
				inner join ".$this->table_user." u on a.user_id = u.usr_id
			where `id` = ".$id
		);
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