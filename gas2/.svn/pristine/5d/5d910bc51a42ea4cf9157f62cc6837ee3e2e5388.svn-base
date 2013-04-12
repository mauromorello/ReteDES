<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
	  $menu_aperto=menu_lat::user;
			  //sono io
	  $my_user_level=user_level($id_user);	  
	   
			  
	  if($my_user_level<1){
		pussa_via();  
		exit;   
	  }
	  
	  
	  
	  
	  
	  $query="SELECT
				retegas_messaggi.id_messaggio,
				maaking_users.fullname,
				retegas_ordini.descrizione_ordini,
				retegas_messaggi.messaggio,
				retegas_messaggi.timbro,
				retegas_messaggi.tipo,
				retegas_messaggi.tipo2,
				retegas_messaggi.valore,
				retegas_messaggi.query,
				retegas_messaggi.id_user,
				retegas_messaggi.id_ordine
				FROM
				retegas_messaggi
				Left Join maaking_users ON maaking_users.userid = retegas_messaggi.id_user
				Left Join retegas_ordini ON retegas_messaggi.id_ordine = retegas_ordini.id_ordini
				";
	  $query_ord="order by id_messaggio DESC
				 ";
	  $query_lim="LIMIT 200
				 ";           
	  
	  switch ($do){
		case "del":
		$que = "DELETE FROM retegas_messaggi WHERE retegas_messaggi.id_messaggio='$id' LIMIT 1";      
		$result= mysql_query($que);
		$msg = "Messaggio cancellato";
		
		case "all":
			$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">TUTTI I MESSAGGI</div><br>';
			$query_filter="";
			if(!empty($ord)){
			$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">TUTTI I MESSAGGI ORDINE '.$ord.' - '.descrizione_ordine_from_id_ordine($ord).'</div><br>';
			$query_filter="WHERE retegas_messaggi.id_ordine = '$ord'
							";
			}
			if(!empty($use)){
			$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">TUTTI I MESSAGGI UTENTE '.$use.' - '.fullname_from_id($use).'</div><br>';    
			$query_filter="WHERE retegas_messaggi.id_user = '$use'
							";
			}
		
		break;
		
		case "lis":
		$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">LISTINI</div><br>';
			$query_filter="WHERE retegas_messaggi.tipo2='LIS'
							";
		break;
		
		case "cro":
		$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">CRON JOBS</div><br>';
			$query_filter="WHERE retegas_messaggi.tipo='CRO'
					";
		break;
	   
	   case "art":
	   $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">VARIAZIONE ORDINE -> ARTICOLI</div><br>';
			$query_filter="WHERE retegas_messaggi.tipo2='ART'
			";
	   break;
	   
	   case "ema":
	   $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">EMAIL AUTOMATICHE</div><br>';
			$query_filter="WHERE retegas_messaggi.tipo='EML' AND retegas_messaggi.tipo2='AUT'
			";
	   break;
	   
	   case "emm":
	   $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">EMAIL MANUALI</div><br>';
			$query_filter="WHERE retegas_messaggi.tipo='EML' AND retegas_messaggi.tipo2='MAN'
			";
	   break;
	   
	   default:
			$query="";
			pussa_via();
			exit;
	   break;
		
	  }
	  
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

			
	  // menu      
	  include("amministra_menu_core.php");
	  
	  for($s=0;$s<1000;$s++){
	  $colo[$s]=random_color();	      
	  }
	  
	  
	  
	  $result= mysql_query($query.$query_filter.$query_ord.$query_lim);
	  $numfields = mysql_num_fields($result);
	  $h_table .="<hr />";
	  $h_table .= $intestazione;
	  $h_table .= "<table>\n<tr>";
	  $h_table .= '<th> OPZ </th>';
	  $h_table .= '<th> ID </th>';
	  $h_table .= '<th> USER </th>';
	  $h_table .= '<th> ORDINE </th>';
	  $h_table .= '<th> MSG </th>';
	  $h_table .= '<th> DATA </th>';
	  $h_table .= '<th> T1 </th>';
	  $h_table .= '<th> T2 </th>';
	  $h_table .= '<th> VAL </th>';
	  $h_table .= '<th> QRY </th>';
	  $h_table .= "</tr>\n";
   
	  //var_dump($AH[$AH['1']['id']]['help']);
   
	  while ($row = mysql_fetch_row($result)) 
	  {
		  
	   if($my_user_level==5){
		  $posso_cancellare='<a class="awesome red big"   href="amministra_messaggi.php?do=del&id='.$row[0].'">C</a>';
	  }else{
		  $posso_cancellare='';
	  } 
		 
	  $h_table.= '<tr style="background-image:-webkit-gradient(linear,0 0,0 50,from(#E0E0E0),to(#FFFFFF));">
						<td style="vertical-align:middle;">
							'.$posso_cancellare.'
						</td>
						<td style="font-weight:bold;vertical-align:middle;">
							'.$row[0].'
						</td>
						<td style="background-color:#'.$colo[$row[1]].';display: inline-table; vertical-align:middle">
						<a  href="amministra_messaggi.php?do=all&use='.$row[9].'">'.$row[1].'</a>
						</td>
						<td style="background-color:#'.$colo[$row[2]].';display: inline-table; vertical-align:middle">
						<a  href="amministra_messaggi.php?do=all&ord='.$row[10].'">'.$row[2].'</a>
						</td>
						<td>
							<a title="'.$row[3].'">'.substr($row[3],0,20).'...</a>
						</td>
						<td>
							'.$row[4].'
						</td>
						<td>
							'.$row[5].'
						</td>
						<td>
							'.$row[6].'
						</td>
						<td>
							'.$row[7].'
						</td>
						<td>
							<a title="'.($row[8]).'">'.substr($row[8],0,20).'...</a>
						</td>
	  
					</tr>';
	  
	   
	  //$h_table .= '<tr><td></td><td  style="background-color:#'.$colo["$row[2]"].';display: block;">'.implode($row,'</td><td>')."</td></tr>\n"; 
	  }
	  $h_table .= "</table>\n";
	  
		  
	   
	  // END TABELLA ----------------------------------------------------------------------------
	  
	  include ("amministra_main.php");
	  
	  
	  
}else{
	c1_go_away("?q=no_permission");
} 
?>
