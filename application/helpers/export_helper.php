<?php
 
/**
 * $Id: Nov 5, 2009 10:11:45 AM navaro $
 *
 */
ini_set('memory_limit','1024M');
ini_set('max_execution_time','600');

function normal_export($xml_data, $values)
{
	$filename=str_replace(" ","_", $xml_data->title);
	$filename=str_replace(",","_", $filename);
	$export_file = export_as($xml_data->sql, $values, $filename."_".date("d-m-y_His"));
	return $export_file;
}

function annureport($query, $filename, $ext = '.xlsx')
{
	require_once FCPATH.'includes/Classes/PHPExcel.php';
	require_once FCPATH.'includes/Classes/PHPExcel/IOFactory.php';
	$objPHPExcel = new PHPExcel();

	$camp_id=$_POST['param'][1];//"CA00002_05HCB1"
	$camp_type=$_POST['param'][2];//0
	$sql1="SELECT * FROM campagnes WHERE camp_id='$camp_id' AND camp_type='$camp_type'"; //
	$sql2="select * from fiche_data where fd_fiche_id = '%s' order by fd_qord"; // cau hoi
	$sql3="SELECT * FROM annuaires WHERE cont_camp_id='$camp_id' AND cont_camp_type=$camp_type ORDER BY cont_nom, cont_prenom";
	$sql4="SELECT r_q_num,r_ans FROM reponses WHERE r_camp_id='$camp_id' AND r_camp_type='$camp_type' AND r_cont_id='%d' ORDER BY r_q_num";
	
	$db = $GLOBALS['CI']->db;
	$query1=$db->query($sql1);
	$result1=$query1->result();
	$i=1;
	
	if(isset($result1[0]->camp_fiche_id))
	{
		$camp_fiche_id=$result1[0]->camp_fiche_id;
		$camp_name=$result1[0]->camp_descr;
		
		$query2=$db->query(sprintf($sql2, $camp_fiche_id));
		$question=array();
		foreach($query2->result() as $k=>$v)
		{
			$question[]=RevertString($v->fd_qfields);		
		}
		
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$i", $camp_name);
		$excel_fields = array(
					"ContactID",
					"ReunionId",
					"Statut appel",
					"Date DERNIER Appel",
					"Nombre Rappels Effectué",
					"Origine réponse",
					"Spécialité",
					"Fonction",
					"Type Exercice",
					"Civilité",
					"Nom",
					"Prénom",
					"Adresse 1",
					"Adresse 2",
					"Adresse 3",
					"Adresse 4",
					"BP",
					"Lieudit",
					"CP",
					"Ville",
					"Pays",
					"Téléphone",
					"Télecopie",
					"Email",
					"Mobile",
					"Commentaires/Autres",
					"Modification Fiche Medecin"
			);
		$i=3;
		for($x=0;$x<=count($excel_fields)-1;$x++)
		{
	    	$objPHPExcel->setActiveSheetIndex(0)->setCellValue(PHPExcel_Cell::stringFromColumnIndex($x)."$i", $excel_fields[$x]);
			$objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex($x)."$i")->applyFromArray(
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
		}

		$query_count=preg_replace("/select (.*) from/", "SELECT count(*) as total FROM", strtolower($query));
		
		$rs = $GLOBALS['CI']->db->query($query_count);
		$tmp=$rs->result();
		$total=$tmp[0]->total;
		
		$d=10;
		$start = 0;
		$limit = $d;
		$exit=0;
		$i=5;
		
		for($ii=0;$ii<=$total;)
		{
			if($ii>0){
				if($ii+$d<=$total)
				{
					$start=$ii;
					$limit=$d;
				}elseif($ii+$d==$total){
					echo "=";
				}else{
					$start=$ii;
					$limit=$total-$ii;
				}
			}
			$q = $query." limit $start, $limit";
			$rs = $db->query($q);
			foreach($rs->result() as $k=>$v)
			{
				$sql=sprintf($sql4, $v->cont_id);
				//echo "$sql<br/>"; 
				$answer=$db->query($sql);
				$ans=array();
				foreach($answer->result() as $k=>$v2)
				{
					if($v2->r_ans=="OUI")
					{
						$ans[]=1;
					}else if($v2->r_ans=="NON"){
						$ans[]=0;
					}else if($v2->r_ans=="NSP"){
						$ans[]="";
					}else{
						$ans[]="$v2->r_ans";
					}
					//$ans[]=$v2->r_q_num;
					//$ans[]=$v2->r_ans;
				}
				
				$lastcall=date("d.m.y", strtotime($v->cont_lastcall));
				$modified=date("d/m/y", strtotime($v->cont_modified));
				$row = array_merge(array(
					$v->cont_id,
					$v->cont_camp_id,
					$v->cont_callstatus,
					$lastcall,
					$v->cont_counter,
					$v->cont_orirep,
					RevertString($v->cont_specialite),
					RevertString($v->cont_fonction),
					RevertString($v->cont_exercise),
					RevertString($v->cont_civilite),
					RevertString($v->cont_nom),
					RevertString($v->cont_prenom),
					RevertString($v->cont_addr1),
					RevertString($v->cont_addr2),
					RevertString($v->cont_addr3),
					RevertString($v->cont_addr4),
					$v->cont_bp,
					RevertString($v->cont_lieudit),
					$v->cont_cp,
					RevertString($v->cont_ville),
					RevertString($v->cont_pays),
					$v->cont_telephone,
					$v->cont_telecopie,
					$v->cont_email,
					$v->cont_mobile,
					RevertString($v->cont_commentaires),
					$modified
				), $ans);
				//print_r($row);die();

				for($x=0;$x<=count($row)-1;$x++)
				{
	    			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(PHPExcel_Cell::stringFromColumnIndex($x)."$i", $row[$x]);
				}
				$i++;
			}
			$ii=$ii+$d;
		}
	}
	
	if(isset($x) && $x>=1)
	{
		$objPHPExcel->getActiveSheet()->setAutoFilter("A3:".PHPExcel_Cell::stringFromColumnIndex($x).$i);	
	}
			
	$objPHPExcel->getActiveSheet()->setTitle('Phoning manager');		
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save(FCPATH."reports/$filename$ext");
	return array("$filename$ext", $i);
}

/*
function annureport($query, $filename="/tmp/data.xls")
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
	
	$camp_id=$_POST['param'][1];//"CA00002_05HCB1"
	$camp_type=$_POST['param'][2];//0
	$sql1="SELECT * FROM campagnes WHERE camp_id='$camp_id' AND camp_type='$camp_type'"; //
	$sql2="select * from fiche_data where fd_fiche_id = '%s' order by fd_qord"; // cau hoi
	$sql3="SELECT * FROM annuaires WHERE cont_camp_id='$camp_id' AND cont_camp_type=$camp_type ORDER BY cont_nom, cont_prenom";
	$sql4="SELECT r_q_num,r_ans FROM reponses WHERE r_camp_id='$camp_id' AND r_camp_type='$camp_type' AND r_cont_id='%d' ORDER BY r_q_num";
	
	$db = $GLOBALS['CI']->db;
	$query1=$db->query($sql1);
	$result1=$query1->result();
	$i=1;
	
	if(isset($result1[0]->camp_fiche_id))
	{
		$camp_fiche_id=$result1[0]->camp_fiche_id;
		$camp_name=$result1[0]->camp_descr;
		
		$query2=$db->query(sprintf($sql2, $camp_fiche_id));
		$question=array();
		foreach($query2->result() as $k=>$v)
		{
			$question[]=RevertString($v->fd_qfields);		
		}
		
		$y=0; $x=0;
		$worksheet->write_string($y, $x, $camp_name); 
		$y=1; // i = 1, row = 1
		$excel_fields = array(
					"ContactID",
					"ReunionId",
					"Statut appel",
					"Date DERNIER Appel",
					"Nombre Rappels Effectué",
					"Origine réponse",
					"Spécialité",
					"Fonction",
					"Type Exercice",
					"Civilité",
					"Nom",
					"Prénom",
					"Adresse 1",
					"Adresse 2",
					"Adresse 3",
					"Adresse 4",
					"BP",
					"Lieudit",
					"CP",
					"Ville",
					"Pays",
					"Téléphone",
					"Télecopie",
					"Email",
					"Mobile",
					"Commentaires/Autres",
					"Modification Fiche Medecin"
				);
			$y=2;
			for(;$x<=count($excel_fields)-1;$x++)
			{
				$worksheet->write_string($y, $x, $excel_fields[$x], $format_title);	
			}

			$query_count=preg_replace("/select (.*) from/", "SELECT count(*) as total FROM", strtolower($query));
			
			$rs = $GLOBALS['CI']->db->query($query_count);
			$tmp=$rs->result();
			$total=$tmp[0]->total;
			
			$d=500;
			$start = 0;
			$limit = $d;
			$exit=0;
			
			for($ii=0;$ii<=$total;)
			{
				if($ii>0){
					if($ii+$d<=$total)
					{
						$start=$ii;
						$limit=$d;
					}elseif($ii+$d==$total){
						echo "=";
					}else{
						$start=$ii;
						$limit=$total-$ii;
					}
				}
				$q = $query." limit $start, $limit";
				$rs = $db->query($q);
				foreach($rs->result() as $k=>$v)
				{
					$sql=sprintf($sql4, $v->cont_id);
					//echo "$sql<br/>"; 
					$answer=$db->query($sql);
					$ans=array();
					foreach($answer->result() as $k=>$v2)
					{
						if($v2->r_ans=="OUI")
						{
							$ans[]=1;
						}else if($v2->r_ans=="NON"){
							$ans[]=0;
						}else if($v2->r_ans=="NSP"){
							$ans[]="";
						}else{
							$ans[]="$v2->r_ans";
						}
						//$ans[]=$v2->r_q_num;
						//$ans[]=$v2->r_ans;
					}
					
					$lastcall=date("d.m.y", strtotime($v->cont_lastcall));
					$modified=date("d/m/y", strtotime($v->cont_modified));
					$row = array_merge(array(
						$v->cont_id,
						$v->cont_camp_id,
						$v->cont_callstatus,
						$lastcall,
						$v->cont_counter,
						$v->cont_orirep,
						RevertString($v->cont_specialite),
						RevertString($v->cont_fonction),
						RevertString($v->cont_exercise),
						RevertString($v->cont_civilite),
						RevertString($v->cont_nom),
						RevertString($v->cont_prenom),
						RevertString($v->cont_addr1),
						RevertString($v->cont_addr2),
						RevertString($v->cont_addr3),
						RevertString($v->cont_addr4),
						$v->cont_bp,
						RevertString($v->cont_lieudit),
						$v->cont_cp,
						RevertString($v->cont_ville),
						RevertString($v->cont_pays),
						$v->cont_telephone,
						$v->cont_telecopie,
						$v->cont_email,
						$v->cont_mobile,
						RevertString($v->cont_commentaires),
						$modified
					), $ans);
					for($x=0;$x<=count($excel_fields)-1;$x++)
					{
						$worksheet->write_string($y+$i, $x, $row[$x]);						
					}
					$i++;
			  	}
				$ii=$ii+$d;
			}
			$workbook->close();			
		}
}
*/