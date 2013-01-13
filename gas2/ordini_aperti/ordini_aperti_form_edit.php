<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);
		
 //echo id_referente_ordine_globale($id)." >>". $id_user;
 if(empty($data_1)){$data_1=$id;};
			  

		
if(stato_from_id_ord($data_1)<>2){
		pussa_via();         
		exit; 
		}
if(ordine_inesistente($data_1)){
		pussa_via();         
		exit;    
	
}

	 

if (id_referente_ordine_globale($data_1)<>$id_user){
	$msg = "Birbante, stai cercando di modificare un ordine non tuo";
	include ("ordini_aperti_table.php");
exit;    
}

	 
	 $query_visual = "SELECT * FROM retegas_ordini 
						WHERE  (id_ordini='$data_1') LIMIT 1";
	  $result = mysql_query($query_visual);
	  $row = mysql_fetch_array($result);              
	  
	  
	  // VALORI DELLE CELLE da DB---------------------
	 
	  
if ($do=="mod"){
	  
		 //DEBUG
	  //echo $d1."=(".$data_1.") ";
	  //echo "Data_2:(".$data_2.") ";
	  //echo "Data_3:(".$data_3.") <BR>";
	  //echo "Data_4:(".$data_4.") ";
	  //echo "Data_5:(".$data_5.") ";
	 //echo "Data_6:(".$data_6.") <BR>";
	  //DEBUG

	  // se è vuoto
	  if (empty($data_2)){$msg.="Devi inserire la data di chiusura<br>";$e_empty++;};
	  //if (empty($data_3)){$msg.="Devi inserire la data di consegna merce<br>";$e_empty++;};
	  if (empty($data_4)){$data_4=0;};
	  if (empty($data_5)){$data_5=0;};
  
	  $data_19 = sanitize($data_19);
	  // se il costo è valuta valida
	  
	  if (!valuta_valida($data_4)){$msg.="Il costo gestione non è in formato valido.<br>";$e_currency++;}; 
	  if (!valuta_valida($data_5)){$msg.="Il costo trasporto non è in formato valido.<br>";$e_currency++;}; 
	   
	   // ----------------------- Controllo Data chiusura > oggi         (L7<oggi)
			 
		  if (gas_mktime($data_2)<gas_mktime(date("d/m/Y H:i"))){
				$msg.="Data di chiusura gia' passata<br>";
				$e_logical++;

		  }
		  //if (gas_mktime($data_3)<gas_mktime($data_2)){
		//		$msg.="Data di consegna merce antecedente a quella di chiusura ordine<br>";
		//		$e_logical++;
		//  }
	 
	 
  
	  
	 
	  
	  
	  $e_total = $e_empty + $e_logical + $e_currency;
	  
	  //echo "e empty:(".$e_empty.") <BR>";
	  //echo "e_logical:(".$e_logical.") <BR>";
	  //echo "e_currency:(".$e_currency.") <BR>";
	  
	  
	  //echo "e total:(".$e_total.") <BR>";
	  
	  if($e_total==0){
		// Se la chiusura è oggi gli cambio lo stato 2 aperto 3 = chiuso  
		if (gas_mktime(substr($data_2,0,10))==gas_mktime(date("d/m/Y"))){
			  $L15 = 3;
			  $msg ="ORDINE CHIUSO IMMEDIATAMENTE<br>"; 
			  $go= "../ordini_chiusi/ordini_chiusi_table.php"; 
			  }else{
			  $L15 = 2;
			  $go= "ordini_aperti_form.php"; 
			  }
		  
		// CONVERTITEVI
		
		
		// Setto ad "ora" la data di chiusura
		$data_2=conv_date_to_db(date("d/m/Y H:i"));
	   
		//$data_3=conv_date_to_db($data_3);
		

		
		
		  
		// QUERY INSERT
		$my_query="UPDATE retegas_ordini 
			  SET 
			  retegas_ordini.data_chiusura= '$data_2',
			  retegas_ordini.costo_gestione = '$data_4',
			  retegas_ordini.costo_trasporto = '$data_5',
			  retegas_ordini.id_stato = '$L15',
			  retegas_ordini.note_ordini = '$data_19' 
			  WHERE 
			  retegas_ordini.id_ordini = '$data_1' LIMIT 1;";
		// echo $my_query;                                         
		
		//INSERT BEGIN ---------------------------------------------------------
		 $result = $db->sql_query($my_query);
		 
		 if (is_null($result)){
			 unset($do);
			$msg = "Errore nella modifica dell'ordine";
			include ("../index.php");
			exit;  
		}else{
			unset($do);
			$id = $data_1; 
			$nome_ordine = descrizione_ordine_from_id_ordine($id);
			
			$messa = "L'utente $fullname ha modificato l'ordine $id ($nome_ordine)";
			
			
			log_me($id,$id_user,"ORD","MOD",$messa,0,$my_query);
			
			$msg .= "Ordine Modificato";
			
			include($go);
			exit;  
		};
		
		//INSERT END --------------------------------------------------------- 
		
		
		
		 
		  
	  }else{
	   $msg.="<br>Verifica i dati immessi e riprova"; 
	  unset($do);    
	  $id=$data_1;
	  include("ordini_aperti_form_edit.php");  
	  exit;  
		  
	  } // se non ci sono errori
	  
}else{
	$data_1=$row["id_ordini"];
	$data_2=conv_date_from_db($row["data_chiusura"]);
	//$data_3=conv_date_from_db($row["data_merce"]);
	$data_4=$row["costo_gestione"];
	$data_5=$row["costo_trasporto"];
	
	$data_19=$row["note_ordini"];
	  
	$data_7=""; 
	$data_8=""; 
	$data_9=""; 
	$data_10=""; 
	$data_11=""; 
	$data_12=""; 
} // Se do =mod
	  
	  // Controlli sui dati --------------------------------- CCCC

	  
	  // MENU APERTO
	  $menu_aperto=1;
			  
	  // TITOLO FORM_ADD
	  
	  $titolo_tabella="Modifica l'ordine ID $id";
	  
	  
	  
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"30%\"";
	  $col_2="width=\"30%\""; 
	  
	  // OPZIONI
	  
	  // FORM -------------------------------------------
	  
	  $title_form = "<form name=\"Modifica ordine\" method=\"POST\" action=\"ordini_aperti_form_edit.php\">";
	  $submit_form ="<input class=\"large green awesome\" style=\"margin:20px;\" type=\"submit\" value=\"Modifica\">";  
	  
	  // Campi
	  
	   //descrizione
	  $input_2 = "<input type=\"text\" name=\"data_2\" size=\"28\" value=\"$data_2\" id=\"datepicker\">"; //descrizione 
	  //$input_3 = "<input type=\"text\" name=\"data_3\" size=\"28\" value=\"$data_3\" id=\"datepicker2\">"; //descrizione 
	  $input_4 = "<input type=\"text\" name=\"data_4\" size=\"28\" value=\"$data_4\">"; //descrizione 
	  $input_5 = "<input type=\"text\" name=\"data_5\" size=\"28\" value=\"$data_5\">"; //descrizione 
	  $input_19 ='<textarea COLS="50"  name="data_19">'.$data_19.'</textarea>';
	  
	  $input_hidden =   "<input type=\"hidden\" name=\"do\"  value=\"mod\">";
	  $input_hidden_2 = "<input type=\"hidden\" name=\"data_1\"  value=\"$data_1\">";   //id_ditta
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	  $h_table .= " <div class=\"ui-widget-header ui-corner-all padding_6px\"> 
					<div style=\"margin-bottom:16px;\"\">$titolo_tabella</div>
					$title_form
					<table>
					<tr class=\"odd\">
						<th $col_1>Identificativo ordine</th>
						<td $col_2>$data_1</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Data chiusura ordine <br> <div class=\"ui-widget ui-state-error ui-corner-all padding_6px\">Inserendo la data odierna come data di chiusura, l'ordine verrà chiuso IMMEDIATAMENTE</div></th>
						<td $col_2>$input_2</td>
					</tr>

					<tr class=\"odd\">
						<th $col_1>Costo Gestione</th>
						<td $col_2>$input_4</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Costo Trasporto</th>
						<td $col_2>$input_5</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Note ordine</th>
						<td $col_2>$input_19</td>
					</tr>
					</table>
					$input_hidden $input_hidden_2
					<center>
					$submit_form
					</center>
					</form>
					</div>";

	  // END TABELLA ----------------------------------------------------------------------------
	  
	  // HEADER HTML
	  //$msg ="Pagina non ancora funzionante";
	  $posizione ="ORDINI APERTI -> Gestione -> <b>Modifica</b>";
	  $datetimepicker1 = "#datepicker";
	  $datetimepicker2 = "#datepicker2";
	  
	  include ("ordini_aperti_main.php");
 
}else{
	pussa_via();
} 
?>
