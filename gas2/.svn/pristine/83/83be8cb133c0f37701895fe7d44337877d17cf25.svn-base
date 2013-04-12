<?php

include_once ("../rend.php");
//include_once ("../Swift-4.0.6/mailer.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname=fullname_from_id($id_user);
		
	  
		$conversion_chars = array (    "à" => "&agrave;", 
							   "è" => "&egrave;", 
							   "é" => "&egrave;", 
							   "ì" => "&igrave;", 
							   "ò" => "&ograve;", 
							   "ù" => "&ugrave;"); 

		$msg_mail = str_replace (array_keys ($conversion_chars), array_values  ($conversion_chars), $msg_mail);  
	  
	  
	  
		if(ordine_io_cosa_sono($id,$id_user)==0){
			pussa_via();       
		exit;    
		}
		
		if(stato_from_id_ord($id)<>2){
			pussa_via();         
		exit; 
		}
	  
	  
	  if($do=="1"){           // REFERENTE GAS -----> REFERENTE ORDINE
		  
		  $da_chi = fullname_from_id($id_user);
		  $mail_da_chi = id_user_mail($id_user);
		
		  $verso_chi = fullname_referente_ordine_globale($id); 
		  $mail_verso_chi = mail_referente_ordine_globale($id);
		  
		  //echo fullname_referente_ordine_globale($id)."<br>";  
		 // echo mail_referente_ordine_globale($id)."<br>";
		  //echo fullname_from_id($id_user)."<br>";
		 // echo id_user_mail($id_user)."<br>";  
			
		  $soggetto = "[RETEGAS AP] - da $da_chi - Comunicazione";
		  manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
			
		  $msg="Mail correttamente inviata a $verso_chi";
		  unset($do);
		  include ("ordini_aperti_form.php");
		  exit;
	  }
	  
	  if($do=="2"){           // UTENTE -----> REFERENTE GAS
		  
		  $da_chi = fullname_from_id($id_user);
		  $mail_da_chi = id_user_mail($id_user);
		
		  $verso_chi = fullname_referente_ordine_proprio_gas($id,$gas); 
		  $mail_verso_chi = mail_referente_ordine_proprio_gas($id,$gas);
		  
			
			
		  $soggetto = "[RETEGAS AP] - da $da_chi - Comunicazione";
		  manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
			
		  $msg="Mail correttamente inviata a $verso_chi";
		  unset($do);
		  include ("ordini_aperti_form.php");
		  exit;
	  }
	  
	  if($do=="3"){           // REFERENTE -----> UTENTI PROPRIO GAS
		// CONTROLLARE SE USER E' REFERENTE GAS
		  $da_chi = fullname_from_id($id_user);
		  $mail_da_chi = id_user_mail($id_user);
		
		  
		$descrizione_ordine = descrizione_ordine_from_id_ordine($id);
		
		$qry=" SELECT
		maaking_users.fullname,
		maaking_users.email
		FROM
		maaking_users
		Inner Join retegas_dettaglio_ordini ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
		WHERE
		retegas_dettaglio_ordini.id_ordine =  '$id' AND
		maaking_users.id_gas =  '$gas'
		GROUP BY
		maaking_users.fullname,
		maaking_users.email;
		";
		$result = $db->sql_query($qry); 
		while ($row = mysql_fetch_array($result)){
		
		//echo $row[0] ." - ". $row[1]."<br />";
		$verso_chi[] = $row[0] ;
		$mail_verso_chi[] = $row[1] ;
		$lista_destinatari .= $row[0]."<br>"; 
		//$verso_chi = $row[0]; 
		//$mail_verso_chi = $row[1];
		
		}

		$soggetto = "[RETEGAS AP] - [REFERENTE GAS] $da_chi per ordine $id ($descrizione_ordine)";
		
		  manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
			
		  $msg="Mail correttamente inviata a : <br>$lista_destinatari";
		  unset($do);
		  include ("ordini_aperti_form.php");
		  exit;
	  }
	  
	  if($do=="4"){           // REFERENTE -----> TUTTI UTENTI ORDINE
	   
	   // CONTROLLARE SE USER E' REFERENTE ORDINE   
		  $da_chi = fullname_from_id($id_user);
		  $mail_da_chi = id_user_mail($id_user);
		
		  
		$descrizione_ordine = descrizione_ordine_from_id_ordine($id);
		
		$qry=" SELECT
		maaking_users.fullname,
		maaking_users.email
		FROM
		maaking_users
		Inner Join retegas_dettaglio_ordini ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
		WHERE
		retegas_dettaglio_ordini.id_ordine =  '$id'
		GROUP BY
		maaking_users.fullname,
		maaking_users.email;
		";
		$result = $db->sql_query($qry); 
		while ($row = mysql_fetch_array($result)){
		
		//echo $row[0] ." - ". $row[1]."<br />";
		$verso_chi[] = $row[0] ;
		$mail_verso_chi[] = $row[1] ;
		$lista_destinatari .= $row[0]."<br>"; 
		//$verso_chi = $row[0]; 
		//$mail_verso_chi = $row[1];
		
		}

		$soggetto = "[RETEGAS AP] - [REFERENTE ORDINE] $da_chi per ordine $id ($descrizione_ordine)";
		
		  manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
			
		  $msg="Mail correttamente inviata a : <br>$lista_destinatari";
		  unset($do);
		  include ("ordini_aperti_form.php");
		  exit;
	  } 
	  
	  if($do=="5"){           // REFERENTE ORDIND-----> REFERENTI GAS
	   
	   // CONTROLLARE SE USER E' REFERENTE ORDINE   
		  $da_chi = fullname_from_id($id_user);
		  $mail_da_chi = id_user_mail($id_user);
		
		  
		$descrizione_ordine = descrizione_ordine_from_id_ordine($id);
		
	   $qry="  SELECT
					maaking_users.fullname,
					maaking_users.email,
					maaking_users.id_gas
					FROM
					maaking_users
					Inner Join retegas_referenze ON retegas_referenze.id_utente_referenze = maaking_users.userid
					WHERE
					retegas_referenze.id_ordine_referenze = '$id'
					GROUP BY
					maaking_users.fullname,
					maaking_users.email,
					maaking_users.id_gas
					";
		$result = $db->sql_query($qry);
		$lista_destinatari ="";
		while ($row = mysql_fetch_array($result)){
			if($gas<>$row[2]){
				$verso_chi[] = $row[0] ;
				$mail_verso_chi[] = $row[1] ;
				$lista_destinatari .= $row[0]." (".gas_nome($row[2])."); <br>";
				}

		}// END WHILE

		$soggetto = "[RETEGAS AP] - [REFERENTE ORDINE] $da_chi per ordine $id ($descrizione_ordine)";
		
		  manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
			
		  $msg="Mail correttamente inviata a : <br>$lista_destinatari";
		  unset($do);
		  include ("ordini_aperti_form.php");
		  exit;
	  }
	  
	  if($do=="6"){           // REFERENTE ORDIND-----> BACINO UTENZA
	   
	   // CONTROLLARE SE USER E' REFERENTE ORDINE   
		  $da_chi = fullname_from_id($id_user);
		  $mail_da_chi = id_user_mail($id_user);
		
		  
		$descrizione_ordine = descrizione_ordine_from_id_ordine($id);
		
	   $qry="SELECT
				maaking_users.fullname,
				maaking_users.email,
				maaking_users.user_site_option,
				retegas_referenze.id_gas_referenze,
				retegas_gas.descrizione_gas,
				maaking_users.userid
				FROM
				retegas_ordini
				Inner Join retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze
				Inner Join maaking_users ON retegas_referenze.id_gas_referenze = maaking_users.id_gas
				Inner Join retegas_gas ON retegas_referenze.id_gas_referenze = retegas_gas.id_gas
				WHERE
				retegas_ordini.id_ordini =  '$id'";
		$result = $db->sql_query($qry);
		$lista_destinatari ="";
		while ($row = mysql_fetch_array($result)){
			if($row[2] & opti::aggiornami_nuovi_ordini){ 
				$verso_chi[] = $row[0] ;
				$mail_verso_chi[] = $row[1] ;
				$lista_destinatari .= $row[0]." (".$row[4]."); <br>";
				}

		}// END WHILE

		$soggetto = "[RETEGAS AP] - [REFERENTE ORDINE] $da_chi per ordine $id ($descrizione_ordine)";
		
		  manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
			
		  $msg="Mail correttamente inviata a : <br>$lista_destinatari";
		  unset($do);
		  include ("ordini_aperti_form.php");
		  exit;
	  }
	  
	  
	  
	  
	  // MENU APERTO
	  $menu_aperto=3;
	  
switch ($mail_type){

	   case "1":
			$titolo__form_mail="Manda un messaggio al referente globale ordine";
			$lista_destinatari = fullname_referente_ordine_globale($id);
			$posizione_estesa ="Referente Ordine";
			break;
	   case "2":
			
			$titolo__form_mail="Manda un messaggio al referente del tuo GAS per questo ordine";
			$lista_destinatari = fullname_referente_ordine_proprio_gas($id,$gas);
			$posizione_estesa ="Referente GAS";
			break; 
	   case "3":
			$titolo__form_mail="Manda un messaggio partecipanti a questo ordine del tuo GAS";
			$qry="  SELECT
					maaking_users.fullname,
					maaking_users.email
					FROM
					maaking_users
					Inner Join retegas_dettaglio_ordini ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
					WHERE
					retegas_dettaglio_ordini.id_ordine =  '$id' AND
					maaking_users.id_gas =  '$gas'
					GROUP BY
					maaking_users.fullname,
					maaking_users.email
					";
		$result = $db->sql_query($qry);
		$lista_destinatari ="";  
		while ($row = mysql_fetch_array($result)){

		$lista_destinatari .= $row[0]."; ";

		}
		
		$posizione_estesa ="Partecipanti GAS";
		break;
		case "4":
			$titolo__form_mail="Manda un messaggio partecipanti a questo ordine di tutti i GAS";
			$qry="  SELECT
					maaking_users.fullname,
					maaking_users.email,
					maaking_users.id_gas
					FROM
					maaking_users
					Inner Join retegas_dettaglio_ordini ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
					WHERE
					retegas_dettaglio_ordini.id_ordine =  '$id'
					GROUP BY
					maaking_users.fullname,
					maaking_users.email,
					maaking_users.id_gas
					";
		$result = $db->sql_query($qry);
		$lista_destinatari ="";
		while ($row = mysql_fetch_array($result)){
			if($row[2]<>$gas){
				
				$lista_destinatari .= "Utente ".gas_nome($row[2])."; ";
			}else{
				$lista_destinatari .= $row[0]."; ";
				}
		}
		
		$posizione_estesa ="Tutti i partecipanti"; 
		break;
		case "5":
			$titolo__form_mail="Manda un messaggio ai Referenti degli altri GAS di questo ordine";
			$qry="  SELECT
					maaking_users.fullname,
					maaking_users.email,
					maaking_users.id_gas
					FROM
					maaking_users
					Inner Join retegas_referenze ON retegas_referenze.id_utente_referenze = maaking_users.userid
					WHERE
					retegas_referenze.id_ordine_referenze = '$id'
					GROUP BY
					maaking_users.fullname,
					maaking_users.email,
					maaking_users.id_gas
					";
		$result = $db->sql_query($qry);
		$lista_destinatari ="";
		while ($row = mysql_fetch_array($result)){
			if($gas<>$row[2]){
				$lista_destinatari .= $row[0]." (".gas_nome($row[2])."); ";
			}
		}
		$posizione_estesa ="Altri referenti"; 
		break;
		case "6":
			$titolo__form_mail="Manda un messaggio al bacino di potenziali utenti di questo ordine, che hanno accettato di ricevere aggiornamenti da parte del sito.";
			$qry="  SELECT
				maaking_users.fullname,
				retegas_gas.descrizione_gas,
				maaking_users.email,
				maaking_users.user_site_option,
				maaking_users.userid
				FROM
				retegas_ordini
				Inner Join retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze
				Inner Join maaking_users ON retegas_referenze.id_gas_referenze = maaking_users.id_gas
				Inner Join retegas_gas ON retegas_referenze.id_gas_referenze = retegas_gas.id_gas
				WHERE
				retegas_ordini.id_ordini =  '$id'";

		$result = $db->sql_query($qry);
		$lista_destinatari ="";
		while ($row = mysql_fetch_array($result)){
			if($row[3] & opti::aggiornami_nuovi_ordini){
				$lista_destinatari .= " ".$row[0]."; ";
			}
		}
		
		$posizione_estesa ="Potenziali partecipanti"; 
		break;	
}	  
	  
	  
	  // Campi e intestazioni
	  include ("ordini_aperti_sql_core.php");
	  
	  // menu
	  //$pdf_url="test1.php?id=$id";
		  
	  include("ordini_aperti_menu_core.php");
	  
	  // inclusione scheda
	  // ID = ORDINE
	  
	  include ("ordini_aperti_form_scheda.php");	  
	  
	  

$h_table .= " <div class=\"rg_widget rg_widget_helper m6b\">
			<div style=\"margin-bottom:20px;\" >$titolo__form_mail</div> 
						
			";		 
$h_table .=  "
		
				
		<form method=\"POST\" action=\"ordini_aperti_comunica.php\">
		<table>
		<tr class=\"odd\">
			<th $col_1>
			<input class=\"large awesome\" style=\"margin:20px;\" type=\"submit\" value=\"Invia\">
			</th>
			<td $col_2><textarea class =\"ckeditor\" rows=\"5\" name=\"msg_mail\" cols=\"60\"></textarea></td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>Il messaggio sarà inviato a:</th>
			<td $col_2>$lista_destinatari</td>
		</tr>
		</table>
		<input type=\"hidden\" name=\"do\" value=\"$mail_type\">
		<input type=\"hidden\" name=\"id\" value=\"$id\">
		<center>
		</center>
		</form>
		</div>

		";

	  // END TABELLA ----------------------------------------------------------------------------
$posizione = "ORDINI APERTI -> Comunica -> <b>".$posizione_estesa."</b>";	  
 
 $cke = "YESS";
 include ("ordini_aperti_main.php");
 
 
}else{
	pussa_via();
	exit;
} 
?>
