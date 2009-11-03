<?php
function getxmltitle($xmlparams)
{
	$xmlfiles = $xmlparams->get_rows();
	if(count($xmlfiles)>=1)
	{
		foreach($xmlfiles as $k=>$v)
		{
			$v->title_name = fetch_xml_title(FCPATH."/reports/".$v->filename);
		}
	}
	return $xmlfiles;
}
function getforms($row) 
{
	foreach( explode(';', $row->fields) as $k=>$v)
	{
		$a = explode("=", $v);
		$b = explode(".",$a[0]);
		$param = array();
		$info[$a[0]] = input_value(explode(".", $a[1]), $a[0], array());
	}	
	return $info;
}
function findTablename($row)
{
	$a = explode(';', $row->fields);
	$b = explode('=', $a[1]);
	$c = explode('.', $b[1]);
	return $c[0];
}