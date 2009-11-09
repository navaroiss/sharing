<?php

/**
 * $Id: Sep 14, 2009 2:53:10 PM navaro $
 * 
 */
ini_set('memory_limit','1024M');
ini_set('max_execution_time','600');

include_once('xml-simple.php');
include_once('excelwriter.php');
//include_once('specialreport.php');

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
	$inputs = array();
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

function export_as($sql, $params=array(), $filename='excel_report.xls', $ext = '.xlsx')
{
	if(count($params)>=1)
	{
		foreach($params as $k=>$v)
		{
			$value = (is_numeric($v))?$v:"'".$v."'";
			$sql = str_replace("[PARAM.$k]", $value, $sql);
		}
	}
	$data = fetch_db($sql);
	safe_file_as( $data, $filename );
	
	if(is_file(FCPATH."reports/".$filename.$ext))
	{
		return array($filename.$ext, count($data));
	}else{
		return false;
	}
	
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
function colName($coln)
{
	$alpha = array();
	for($i=0;$i<=25;$i++)
	{
		$basic[$i] = chr($i+65);		
	}
	if($coln<=25)
	{
		return $basic[$coln-1]; 
	}else
	{
		$extra = array(
		'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
		'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
		'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
		'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
		'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ',
		'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'FZ',
		'GA', 'GB', 'GC', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GJ', 'GK', 'GL', 'GM', 'GN', 'GO', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GV', 'GW', 'GX', 'GY', 'GZ',
		'HA', 'HB', 'HC', 'HD', 'HE', 'HF', 'HG', 'HH', 'HI', 'HJ', 'HK', 'HL', 'HM', 'HN', 'HO', 'HP', 'HQ', 'HR', 'HS', 'HT', 'HU', 'HV', 'HW', 'HX', 'HY', 'HZ',
		'IA', 'IB', 'IC', 'ID', 'IE', 'IF', 'IG', 'IH', 'II', 'IJ', 'IK', 'IL', 'IM', 'IN', 'IO', 'IP', 'IQ', 'IR', 'IS', 'IT', 'IU', 'IV', 'IW', 'IX', 'IY', 'IZ',
		'JA', 'JB', 'JC', 'JD', 'JE', 'JF', 'JG', 'JH', 'JI', 'JJ', 'JK', 'JL', 'JM', 'JN', 'JO', 'JP', 'JQ', 'JR', 'JS', 'JT', 'JU', 'JV', 'JW', 'JX', 'JY', 'JZ',
		'KA', 'KB', 'KC', 'KD', 'KE', 'KF', 'KG', 'KH', 'KI', 'KJ', 'KK', 'KL', 'KM', 'KN', 'KO', 'KP', 'KQ', 'KR', 'KS', 'KT', 'KU', 'KV', 'KW', 'KX', 'KY', 'KZ',
		'LA', 'LB', 'LC', 'LD', 'LE', 'LF', 'LG', 'LH', 'LI', 'LJ', 'LK', 'LL', 'LM', 'LN', 'LO', 'LP', 'LQ', 'LR', 'LS', 'LT', 'LU', 'LV', 'LW', 'LX', 'LY', 'LZ',
		'MA', 'MB', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MI', 'MJ', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ',
		'NA', 'NB', 'NC', 'ND', 'NE', 'NF', 'NG', 'NH', 'NI', 'NJ', 'NK', 'NL', 'NM', 'NN', 'NO', 'NP', 'NQ', 'NR', 'NS', 'NT', 'NU', 'NV', 'NW', 'NX', 'NY', 'NZ',
		'OA', 'OB', 'OC', 'OD', 'OE', 'OF', 'OG', 'OH', 'OI', 'OJ', 'OK', 'OL', 'OM', 'ON', 'OO', 'OP', 'OQ', 'OR', 'OS', 'OT', 'OU', 'OV', 'OW', 'OX', 'OY', 'OZ',
		'PA', 'PB', 'PC', 'PD', 'PE', 'PF', 'PG', 'PH', 'PI', 'PJ', 'PK', 'PL', 'PM', 'PN', 'PO', 'PP', 'PQ', 'PR', 'PS', 'PT', 'PU', 'PV', 'PW', 'PX', 'PY', 'PZ',
		'QA', 'QB', 'QC', 'QD', 'QE', 'QF', 'QG', 'QH', 'QI', 'QJ', 'QK', 'QL', 'QM', 'QN', 'QO', 'QP', 'QQ', 'QR', 'QS', 'QT', 'QU', 'QV', 'QW', 'QX', 'QY', 'QZ',
		'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG', 'RH', 'RI', 'RJ', 'RK', 'RL', 'RM', 'RN', 'RO', 'RP', 'RQ', 'RR', 'RS', 'RT', 'RU', 'RV', 'RW', 'RX', 'RY', 'RZ',
		'SA', 'SB', 'SC', 'SD', 'SE', 'SF', 'SG', 'SH', 'SI', 'SJ', 'SK', 'SL', 'SM', 'SN', 'SO', 'SP', 'SQ', 'SR', 'SS', 'ST', 'SU', 'SV', 'SW', 'SX', 'SY', 'SZ',
		'TA', 'TB', 'TC', 'TD', 'TE', 'TF', 'TG', 'TH', 'TI', 'TJ', 'TK', 'TL', 'TM', 'TN', 'TO', 'TP', 'TQ', 'TR', 'TS', 'TT', 'TU', 'TV', 'TW', 'TX', 'TY', 'TZ',
		'UA', 'UB', 'UC', 'UD', 'UE', 'UF', 'UG', 'UH', 'UI', 'UJ', 'UK', 'UL', 'UM', 'UN', 'UO', 'UP', 'UQ', 'UR', 'US', 'UT', 'UU', 'UV', 'UW', 'UX', 'UY', 'UZ',
		'VA', 'VB', 'VC', 'VD', 'VE', 'VF', 'VG', 'VH', 'VI', 'VJ', 'VK', 'VL', 'VM', 'VN', 'VO', 'VP', 'VQ', 'VR', 'VS', 'VT', 'VU', 'VV', 'VW', 'VX', 'VY', 'VZ',
		'WA', 'WB', 'WC', 'WD', 'WE', 'WF', 'WG', 'WH', 'WI', 'WJ', 'WK', 'WL', 'WM', 'WN', 'WO', 'WP', 'WQ', 'WR', 'WS', 'WT', 'WU', 'WV', 'WW', 'WX', 'WY', 'WZ',
		'XA', 'XB', 'XC', 'XD', 'XE', 'XF', 'XG', 'XH', 'XI', 'XJ', 'XK', 'XL', 'XM', 'XN', 'XO', 'XP', 'XQ', 'XR', 'XS', 'XT', 'XU', 'XV', 'XW', 'XX', 'XY', 'XZ',
		'YA', 'YB', 'YC', 'YD', 'YE', 'YF', 'YG', 'YH', 'YI', 'YJ', 'YK', 'YL', 'YM', 'YN', 'YO', 'YP', 'YQ', 'YR', 'YS', 'YT', 'YU', 'YV', 'YW', 'YX', 'YY', 'YZ',
		'ZA', 'ZB', 'ZC', 'ZD', 'ZE', 'ZF', 'ZG', 'ZH', 'ZI', 'ZJ', 'ZK', 'ZL', 'ZM', 'ZN', 'ZO', 'ZP', 'ZQ', 'ZR', 'ZS', 'ZT', 'ZU', 'ZV', 'ZW', 'ZX', 'ZY', 'ZZ'
		);
		return $extra[$coln-25-1];
	}
}
function safe_file_as($data, $filename='excel_report')
{
	// The old solution
  	// wbWriter($data, $filename); // Call the new excel writer system.
	
	
	require_once FCPATH.'includes/Classes/PHPExcel.php';
	require_once FCPATH.'includes/Classes/PHPExcel/IOFactory.php';
	
	$objPHPExcel = new PHPExcel();
	// 65 -> 90 = A -> Z
	$i=1;
	$x=65;
	//print_r($data);
	if(count($data)>=1)
	{
		foreach($data as $k=>$v)
		{
			$x=65;
			foreach($v as $k=>$vl)
			{
				if($i==1)
				{
		            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($x)."$i", $k);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle(chr($x)."$i")->getFont()->setSize(15);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle(chr($x)."$i")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
					$objPHPExcel->getActiveSheet()->getStyle(chr($x)."$i")->applyFromArray(
							array(
								'font'    => array(
									'bold'      => true
								),
								'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
								),
								'borders' => array(
									'top'     => array(
					 					'style' => PHPExcel_Style_Border::BORDER_THIN
					 				)
								),
								'fill' => array(
						 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						  			'rotation'   => 90,
						 			'startcolor' => array(
						 				'argb' => 'FFA0A0A0'
						 			),
						 			'endcolor'   => array(
						 				'argb' => 'FFFFFFFF'
						 			)
						 		)
							)
					);			
				}else{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($x)."$i", $vl);
				}
				//echo chr($x)."$i, $k";
				$x++;
			}
			$i++;
		}
		
	}

	$objPHPExcel->getActiveSheet()->setAutoFilter("A1:".chr($x-1).($i-1));		
	$objPHPExcel->getActiveSheet()->setTitle('Phoning manager');		
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save(FCPATH."reports/$filename.xlsx");  		
}

function HeaderingExcel($filename) {
      header("Content-type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=$filename" );
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
      header("Pragma: public");
}

function wbWriter($data, $filename) // Workbook writer
{
  include_once(FCPATH.'includes/excelwriter/Worksheet.php');
  include_once(FCPATH.'includes/excelwriter/Workbook.php');
    
  HeaderingExcel($filename);
	
  $workbook = new Workbook("-");

  $worksheet =& $workbook->add_worksheet('Phoning manager'); // worksheet 1
  
  $format_title =& $workbook->add_format(); // Format for the headings
  $format_title->set_size(11);
  $format_title->set_align('center');
  $format_title->set_color('white');
  $format_title->set_pattern();
  $format_title->set_fg_color('green');
  $format_title->set_bold();
  
  $format_cells =& $workbook->add_format(); // Format for the cells
  $format_cells->set_size(10);
  $format_cells->set_align('left');
  $format_cells->set_color('black');
  $format_cells->set_pattern();
  $format_cells->set_fg_color('gray');
  $format_cells->set_border(1);
  
  //print_r($data);
  
  $i=0;
  foreach($data as $k=>$v)
  {
  	$y=0;
	foreach($v as $k=>$vl)
	{
		if($i==0)
		{
			$worksheet->write_string($i, $y, $k, $format_title);	
		}else{
			$worksheet->write_string($i, $y, $vl, $format_cells);
		}
	  	$y++;
	}
	$i++;
   }
  
   $workbook->close();	
}

?>
