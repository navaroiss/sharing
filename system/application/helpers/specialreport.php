<?php

/**
 * $Id: Oct 2, 2009 11:16:48 AM navaro $
 * 
 */
	function annureport($query, $filename="/tmp/data.xls")
	{
		$GLOBALS['CI']->load->database('');
		$excel=new ExcelWriter(FCPATH."/reports/".$filename);
		if($excel==false)	
		{
			echo $excel->error;
		}
		
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
			
			$i=0;
			/*$excel->writeLine(array("(024) FMPP DIABETE PROVINS liste  labo"));
	        if($_POST['param'][2]==0)
	            $c = "Campagne MOBILISATION";
	        else
	            $c = "Campagne CONFIRMATION";
			$excel->writeLine(array($c));
			$excel->writeLine(array("Rapport PARTIEL"));
			$excel->writeLine(array("Edité le ".date('d/m/y h:m:s A')));
			*/
			$excel->writeLine(array($camp_name));
			$excel->writeLine(array(
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"",
				"",			"Questions:",));
			$excel->writeLine(array_merge(array(
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
			),$question));
			
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
					$excel->writeLine(array_merge(array(
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
					), $ans));
					
					$i++;
			  	}
				$ii=$ii+$d;
			}
			$excel->close();			
		}
		if($i==0){
			return array($filename, $i);
		}else{
			return array($filename, $i-1);
		}
	}	
function getDataReport()
{
}