<?php

/**
 * $Id: Sep 14, 2009 2:53:10 PM navaro $
 * 
 */

include_once('xml-simple.php');
include_once('excelwriter.php');
include_once('specialreport.php');

function fetch_xml_title($file)
{
	$data = handle_xml($file);
	if(count($data)>=1)
		return $data[0]['content']['title'];
	else
		return false;	
}
function listTableFields($field_name='', $select_table_field="")
{
	$GLOBALS['CI']->load->database('');
	$html = "";
	$tables = $GLOBALS['CI']->db->list_tables();
	if(count($tables)>=1)
	{
		$html = "<select name='$field_name'>";
		foreach($tables as $k=>$table_name)
		{
			$table_fields = $GLOBALS['CI']->db->list_fields( $table_name );
			if(count($table_fields)>=1)
			{
				foreach($table_fields as $k=>$table_field_name)
				{
					if($select_table_field == $table_name.".".$table_field_name)
					{
						$selected = "selected='selected'";
					}else{
						$selected = "";
					}
					$html .= "<option $selected value='".$table_name.".".$table_field_name."'>".$table_name.".".$table_field_name."</option>";
				}
			}
		}
		$html .= "</select>";
	}
	return $html;
}

function handle_xml($file="")
{
	if(is_file($file))
		$content = @file_get_contents( $file );
	else
		$content = @file_get_contents( $_FILES['xmlfile']['tmp_name'] );
	@$parser =& new xml_simple('UTF-8');
	@$parser->parse($content);
	return $parser->tree;
}

function fetch_db($sql)
{
	$GLOBALS['CI']->load->database('');
	$query = $GLOBALS['CI']->db->query($sql);
	return $query->result();
}

function input_define($sql, $values=array())
{
	if(preg_match_all('|\[PARAM\.(\d+)\]|', $sql, $matches))
	{
		$i = 0; 
		foreach($matches[1] as $k=>$v)
		{
			$value_selected = (isset($values[$i]))?$values[$i]:"";
			$inputs[$v] = listTableFields("param[$v]", $value_selected);
			$i++;
		}
	}
	return $inputs;
}

function input_value($fieldname=array(), $name="", $value_selected="")
{
	$values = fetch_db("select distinct ".$fieldname[1]." from ".$fieldname[0]." order by ".$fieldname[1]." DESC");
	$html = "";
	if(count($values)>=1)
	{
		$name = explode(".", $name);
		$html = "<select name='".strtolower($name[0])."[".$name[1]."]'>";
		foreach( $values as $k=>$v)
		{
			if($value_selected == $v->$fieldname[1])
			{
				$selected = "selected='selected'";
			}else{
				$selected = "";
			}
			$html .= "<option $selected value='".$v->$fieldname[1]."'>".$v->$fieldname[1]."</option>";		
		}
		$html .= "</select>";
	}
	return $html;
}

function export_as($sql, $params=array(), $filename='excel_report.xls')
{
	if(count($params)>=1)
	{
		foreach($params as $k=>$v)
		{
			$value = (is_numeric($v))?$v:"'".$v."'";
			$sql = str_replace("[PARAM.$k]", $value, $sql);
		}
	}
	//echo $sql;
	//$filename = str_replace('.xml', '.xls', $filename);
	$data = fetch_db($sql);
	safe_file_as( $data, $filename ); //trim($data[0]['content']['templatename']
	if(is_file(FCPATH."reports/".$filename))
	{
		#@chmod("/somedir/somefile", 777);
		return array($filename, count($data));
	}
	return false;
}

function ConvertToQuery($sql, $params=array())
{
	if(count($params)>=1)
	{
		foreach($params as $k=>$v)
		{
			$value = (is_numeric($v))?$v:"'".$v."'";
			$sql = str_replace("[PARAM.$k]", $value, $sql);
		}
	}
	return $sql;
}
function RevertString($string)
{
	return str_replace("_*", "", $string);
}
function safe_file_as($data, $filename='excel_report.xml')
{
	$fields = $values = array();
	$i = 0;

	$excel=new ExcelWriter(FCPATH."reports/".$filename);
	
	if($excel==false)	
	{
		echo $excel->error;
		die();
	}
		
	foreach($data as $k=>$v)
	{
		foreach($v as $k=>$vl)
		{
			$fields[$i][] = $k;
			$values[$i][] = $vl;	
		}
		if($i==0)
		{
			$excel->writeLine($fields[$i]);
		}
		$excel->writeLine($values[$i]);
		$i++;
  	}
	#$excel->writeCol(24);
  	$excel->close();
}


?>
