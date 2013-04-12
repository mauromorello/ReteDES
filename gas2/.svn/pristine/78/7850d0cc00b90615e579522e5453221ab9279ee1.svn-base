<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		
	   
	 if(empty($data_1)){$data_1=$id;};

// AUTORIZZAZIONI
	 
	 if(stato_from_id_ord($data_1)==2){
		$msg = "Ordine attualmente aperto";
		include ("ordini_chiusi_table.php");         
		exit; 
		}
	 if(ordine_inesistente($data_1)){
	 $msg = "Ordine inesistente";
		include ("ordini_chiusi_table.php");        
		exit;        
		}
	
	 if (id_referente_ordine_globale($data_1)<>$id_user){
	 $msg = "Birbante, stai cercando di modificare un ordine non tuo";
	 include ("ordini_chiusi_table.php");
	exit;    
	}   
// AUTORIZZAZIONI	 
	 
	 $nomordine = descrizione_ordine_from_id_ordine($data_1);
	  
	 $query_visual = "SELECT * FROM retegas_ordini 
						WHERE  (id_ordini='$data_1') LIMIT 1";
	  $result = mysql_query($query_visual);
	  $row = mysql_fetch_array($result);              
	  
	  
	  // VALORI DELLE CELLE da DB---------------------

if ($do=="allow_print"){  
	$sql = "UPDATE `my_retegas`.`retegas_ordini` SET `is_printable` = '1' WHERE `retegas_ordini`.`id_ordini` = '$data_1' LIMIT 1;";
	$db->sql_query($sql);
	log_me($data_1,$id_user,"ORD","MOD","Convalidato ordine $data_1, ($nomordine)",0,$sql);
	$msg .= "Ordine Convalidato con successo";
	$id = $data_1;
	include("ordini_chiusi_form.php");
	exit;          
	
}	 
	  
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
	  if (empty($data_7)){$data_7=0;};
	  // se il costo è valuta valida
	  
	  $data_19=sanitize($data_19);
	  
	  
	  if (!valuta_valida($data_4)){$msg.="Il costo gestione non è in formato valido.<br>";$e_currency++;}; 
	  if (!valuta_valida($data_5)){$msg.="Il costo trasporto non è in formato valido.<br>";$e_currency++;}; 
	   
	   // ----------------------- Controllo Data chiusura > oggi         (L7<oggi)
		// ATTENZIONE TOLTO CONTROLLO SU DATA CHIUSURA INFERIORE DI OGGI	 
		
		//if (gas_mktime($data_2)<gas_mktime(date("d/m/Y"))){
		//		$msg.="Data di chiusura gia' passata<br>";
		//		$e_logical++;

		 // }
		 
		 
		//  if (gas_mktime($data_3)<gas_mktime($data_2)){
		//		$msg.="Data di consegna merce antecedente a quella di chiusura ordine<br>";
		//		$e_logical++;
		//  }
	 
	 // Controlla se il listino e' diverso da quello vecchio
	 // se e' cambiato
		// fa un checksum del vecchio e lo confronta con il nuovo.
		// salva in un array le corrispondenze tra i codici vecchi e nuovi
		// se e' uguale:
			// per ogni ordine_dettaglio, sostituisce il codice dell'articolo vecchio con quello nuovo.
	 $old_listino = id_listino_from_id_ordine($data_1) ;
	 
	 //echo"old_listino: ".$old_listino."<br>";
	 //echo"data_6 : ".$data_6."<br>";
	 
	 
			
	 if ($old_listino<>$data_6){
		 
		 
		 $riga=0;
		 $qry = "SELECT retegas_articoli.codice,retegas_articoli.id_articoli FROM retegas_articoli WHERE retegas_articoli.id_listini = $old_listino ORDER BY retegas_articoli.id_articoli DESC;";
		 $ret = $db->sql_query($qry);
		 while ($row = mysql_fetch_array($ret)){
			$riga++;
			
			$corrispondenze["old"][$riga] = $row["id_articoli"];
			$corrispondenze["cod"][$riga] = $row["codice"];
			$pacco_old = $pacco_old . $row["codice"];
			//echo "corrispondenze[\"old\"][$riga] : ".$row["id_articoli"]."<br>";
			//echo "corrispondenze[\"cod\"][$riga] : ".$row["codice"]."<br>";    
		 }
		 $crc_old=crc16($pacco_old);   
		 $log .= "CRC OLD: -".$crc_old."-<br><br>"; 
		 
		 
		 $riga=0;         
		 $qry = "SELECT retegas_articoli.codice,retegas_articoli.id_articoli FROM retegas_articoli WHERE retegas_articoli.id_listini = $data_6  ORDER BY retegas_articoli.id_articoli DESC;";
		 $ret = $db->sql_query($qry);
		 while ($row = mysql_fetch_array($ret)){
			$riga++;
			$corrispondenze["new"][$riga] = $row["id_articoli"];
			$pacco_new = $pacco_new . $row["codice"];
			//echo "corrispondenze[\"new\"][$riga] : ".$row["id_articoli"]."<br>";     
		 }
		 $crc_new=crc16($pacco_new);
		 $log .= "CRC new: -".$crc_new."-<br>";
	  
		if ($crc_new==$crc_old){
			
			//echo "OK CAMBIO -<br>";
			for ($i = 1; $i <= $riga; $i++){
			  //per ogni articolo in OLD cambio TUTTE le referenze a dettaglio_ordini
			$new_articolo = $corrispondenze["new"][$i] ;
			$old_articolo = $corrispondenze["old"][$i] ;
			$log .= "ART ".$old_articolo."---> ".$new_articolo."<br>";
			
			//le cambio in dettaglio ordini   
			$query_new_list=("UPDATE retegas_dettaglio_ordini SET retegas_dettaglio_ordini.id_articoli = '$new_articolo'
							  WHERE (retegas_dettaglio_ordini.id_ordine = '$data_1') 
							  AND (retegas_dettaglio_ordini.id_articoli = '$old_articolo');");    
			$query_log = $query_log . $query_new_list;
			$result = $db->sql_query($query_new_list);
			$log .= "result : ".$result. "rows affected : ".$db->sql_affectedrows()."<br/>";
			
			//le cambio in distribuzione ordini
			
			//$query_new_list=("UPDATE retegas_dettaglio_ordini SET retegas_dettaglio_ordini.id_articoli = '$new_articolo'
			//                  WHERE (retegas_dettaglio_ordini.id_ordine = '$data_1') 
			 //                 AND (retegas_dettaglio_ordini.id_articoli = '$old_articolo');");    
			//$query_log = $query_log . $query_new_list;
			//$result = $db->sql_query($query_new_list);
			//$log .= "result : ".$result. "rows affected : ".$db->sql_affectedrows()."<br/>";
			
			
			
			
			
			
			
			}//ciclo che passa tutti gli articoli
			$vonuovo = valore_totale_ordine_qarr($data_1);    
			log_me($data_1,$id_user,"ORD","LIS","Modificato listino associato all'ordine $data_1 ($nomordine), adesso vale $vonuovo",0,$query_log);
			
		}else{
			
		$msg.="Il listino che si vuole sostituire non e' compatibile con quello attualmente in uso<br> I listini scambiabili devono avere lo stesso numero di articoli e gli STESSI CODICI ARTICOLO FORNITORE, per poter essere scambiati.";
		$e_logical++;
		
			
		}// se i listini sono uguali
			 
	  
		 
		 
	 }// se il listino e' cambiato     
			
			
			
  
	  
	  
	  
	  
	  $e_total = $e_empty + $e_logical + $e_currency;
	  
	  //echo "e empty:(".$e_empty.") <BR>";
	  //echo "e_logical:(".$e_logical.") <BR>";
	  //echo "e_currency:(".$e_currency.") <BR>";
	  
	  
	  //echo "e total:(".$e_total.") <BR>";
	  
	  if($e_total==0){
		// Se la chiusura è oggi gli cambio lo stato 2 aperto 3 = chiuso  
		if (gas_mktime(date("d/m/Y H:i"))>=gas_mktime($data_2)){
			  $L15 = 3;
			  $go= "ordini_chiusi_form.php";
			  }else{
			  $L15 = 2;
			  $msg ="ORDINE RIESUMATO<br>"; 
			  $go= "../ordini_aperti/ordini_aperti_form.php";
			  log_me($data_1,$id_user,"ORD","MOD","Riesumato ordine $id che era gia\' stato chiuso",0,""); 
		}
		  
		// CONVERTITEVI
		
		$data_2=conv_date_to_db($data_2);
		//$data_3=conv_date_to_db($data_3);
		

		
		
		  
		// QUERY UPDATE
		$my_query="UPDATE retegas_ordini 
			  SET 
			  retegas_ordini.data_chiusura= '$data_2',
			  retegas_ordini.costo_gestione = '$data_4',
			  retegas_ordini.costo_trasporto = '$data_5',
			  retegas_ordini.id_listini = '$data_6',
			  retegas_ordini.is_printable = '$data_7',
			  retegas_ordini.id_stato = '$L15',
			  retegas_ordini.note_ordini = '$data_19' 
			  WHERE 
			  retegas_ordini.id_ordini = '$data_1' LIMIT 1;";
		// echo $my_query;                                         
		
		//INSERT BEGIN ---------------------------------------------------------
		 $result = $db->sql_query($my_query);
		 
		 if (is_null($result)){
			$msg = "Errore nella modifica dell'ordine";
			include ("../index.php");
			exit;  
		}else{
			log_me($data_1,$id_user,"ORD","MOD","Modificato ordine chiuso $data_1, ($nomordine)",0,$my_query);
			$msg .= "Ordine Modificato";
			$id = $data_1;
			include($go);
			exit;  
		};
		
		//INSERT END --------------------------------------------------------- 
		
		
		
		 
		  
	  }else{
	  $msg.="<br>Verifica i dati immessi e riprova";
	  unset($do);    
	  $id=$data_1;
	  include("ordini_chiusi_form_edit.php");  
	  exit;  
		  
	  } // se non ci sono errori
	  
}else{
	$data_1=$row["id_ordini"];
	$data_2=conv_date_from_db($row["data_chiusura"]);
	$data_3=conv_date_from_db($row["data_merce"]);
	$data_4=$row["costo_gestione"];
	$data_5=$row["costo_trasporto"];
	$data_6=$row["id_listini"];
	$data_7=$row["is_printable"];
	if($data_7==1){$checked="checked";}
	$data_19 = $row["note_ordini"]; 
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
	  
	  $title_form = "<form name=\"Modifica ordine\" method=\"POST\" action=\"ordini_chiusi_form_edit.php\">";
	  $submit_form ="<input class=\"large green awesome\" style=\"margin:20px;\" type=\"submit\" value=\"Modifica\">";  
	  
	  // Campi
	  
	   //descrizione
	  $input_2 = "<input type=\"text\" name=\"data_2\" size=\"28\" value=\"$data_2\" id=\"datepicker\">"; //descrizione 
	  //$input_3 = "<input type=\"text\" name=\"data_3\" size=\"28\" value=\"$data_3\" id=\"datepicker2\">"; //descrizione 
	  $input_4 = "<input type=\"text\" name=\"data_4\" size=\"28\" value=\"$data_4\">"; //descrizione 
	  $input_5 = "<input type=\"text\" name=\"data_5\" size=\"28\" value=\"$data_5\">"; //descrizione 
	  $input_6 = "<fieldset> <select name= \"data_6\"> ";
	  $input_7 = "<input type=\"checkbox\" name=\"data_7\" value=\"1\" $checked>";
	  $input_19 ='<textarea COLS="50"  name="data_19">'.$data_19.'</textarea>';
	  
	  $id_ditta = ditta_id_from_listino($data_6);
		
		$result = mysql_query("SELECT * FROM retegas_listini WHERE id_ditte='$id_ditta'");
		$totalrows = mysql_num_rows($result);
		while ($rows = mysql_fetch_array($result)){
				$idtip = $rows[0];
				$descrizionetip = $rows[1];
				if ($idtip == $data_6){
					$input_6 .= "<option value=\"".$idtip ."\" selected=\"selected\" size=\"28\">".$descrizionetip ."  </option>";
				}else{
					$input_6 .= "<option value=\"".$idtip ."\" size=\"28\">".$descrizionetip ."  </option>"; 
				}
		 }//end while
	  $input_6 .="</select>
		</fieldset>"; 
	  
	  
	  
	  
	  
	  
	  $input_hidden =   "<input type=\"hidden\" name=\"do\"  value=\"mod\">";
	  $input_hidden_2 = "<input type=\"hidden\" name=\"data_1\"  value=\"$data_1\">";   //id_ditta
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	  $h_table .= " <div class=\"ui-widget-header ui-corner-all padding_6px\">
					<div class=\"m6b\">$titolo_tabella</div>
					$title_form
					<table>
					<tr class=\"odd\">
						<th $col_1>Identificativo ordine</th>
						<td $col_2>$data_1</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Data chiusura ordine <br>
						<div class=\"ui-widget ui-state-error ui-corner-all padding_6px\">
						Inserendo una data maggiore di quella odierna come data di chiusura, l'ordine verrà RIAPERTO;
						</div>
						</th>
						
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
						<th $col_1>Listino usato <br> Per poter cambiare il listino, occorre che il listino nuovo sia compatibile con quello vecchio. (Deve avere gli stessi articoli)</th>
						<td $col_2>$input_6</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Note ordine</th>
						<td $col_2>$input_19</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>
						
						Abilitazione stampe<br>
						<div class=\"ui-widget ui-state-error ui-corner-all padding_6px\">
						 Se è spuntato permette a tutti gli utenti di stampare la loro scheda, e non permette ulteriori modifiche ai quantitativi arrivati.
						</div>
						</th>
						<td $col_2>$input_7</td>
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
	  $posizione = "ORDINI CHIUSI -> Gestione ordine -> <b>Modifica ordine</b>";
	  $datetimepicker1 = "#datepicker";
	  $datetimepicker2 = "#datepicker2";
	  
	  include ("ordini_chiusi_main.php");
 
}else{
	pussa_via();
} 
?>
