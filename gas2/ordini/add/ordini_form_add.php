<?php

include_once ("../../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$permission = (int)$cookie_read[6];
		 
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);;	
	
	  if(empty($data_17)){$data_17=1;} // senza prezzo 
	  if(empty($data_13)){$data_13=1;} 
	  if(empty($data_18)){$data_18=0;} // comunicazioni
	  // Controlli sui dati --------------------------------- CCCC
	  
	  // PULIZIA CAMPI
	  
	  //$data_1 = puliscistringa($data_1);
	  //$data_2 = puliscistringa($data_2);
	  //$data_3 = puliscistringa($data_3);
	  //$data_4 = puliscistringa($data_4);
	  
	  
	  //controllo se posso
	  //controllo se posso
	  if(!($permission & perm::puo_creare_ordini)){
			$q = "not_allowed";
			include ("../../index.php");
			exit;            
	  }
	  
	  
	  
	 // INTESTAZIONI CAMPI
	  
	  include("/dini_sql.php"); 
	  
	  
	  
	  
	  
	  if ($do=="add"){
		  
	  //include("ordini_debug.php");
		  
		  
	  
		// se è vuoto
		//----------------------------VUOTI
		  if(trim(empty($data_4))){                          //titolo
				 $msg .= "Deve esserci un titolo<br>";
				 $e_empty++;
		  }
		  $data_4=sanitize($data_4);
		  
		  
		  if(trim(empty($data_8))){                          // data chiusura
				$msg .="Data chiusura obbligatoria<br>";
				$e_empty++;
		   }
		  // Data apertura = adesso
		  if(trim(empty($data_7))){   //data apertura
				 $data_7=date("d/m/Y H:i");
		  }
		  if(trim(empty($data_10))){   //costo trasp
				 $data_10=0;
		  }
		  if(trim(empty($data_11))){   //costo gest
				 $data_11=0;
		  }
		  if(trim(empty($data_14))){  //min art
				 $data_14=0;
		  }
		  if(trim(empty($data_15))){  //min scat
				 $data_15=0;
		  }
		   // se i numeri sono negativi
		  if(!is_numeric($data_14)){
				$msg.="Il valore nel campo \"minimo scatole\" non è stato riconosciuto<br>";
				$e_logical++;
		  }
		  if(!is_numeric($data_15)){
				$msg.="Il valore nel campo \"minimo articoli\" non è stato riconosciuto<br>";
				$e_logical++;
		  } 
								 
		  if (($data_14<0)){
				$data_14=0;
		  }
		  if (($data_15<0)){
				$data_15=0;
		  }
		  
		  if(trim(empty($data_13))){ // privato
				 $data_13=0;
		  }
		  if(trim(empty($data_17))){ // senzxa prezzo
				 $data_17=0;
		  }
		  if(trim(empty($data_18))){ // comunicazioni
				 $data_18=0;
		  }
		  if($data_18<0){
			  $data_18=0;
		  }
		   if($data_18>2){
			  $data_18=2;
		  }
		  
		  $data_19 = sanitize($data_19);
		  
		  //-----------------------------------FORMATI e VALORI
		  if (!valuta_valida($data_10)){
				 $msg.="Costo trasporto non riconosciuto<br>";
				 $e_currency++;
		  }
		  if (!valuta_valida($data_11)){
				 $msg.= "Costo gestione non riconosciuto<br>";
				 $e_currency++;
		  }
		  if (!controllodataora($data_8)){
				$msg.="Data chiusura non valida<br>";
				$e_logical++;
		  }
		  if (!controllodataora($data_7)){
				$msg.="Data apertura non valida<br>";
				$e_logical++; 
		  }
		 
		  
  
		  // ----------------------- Controllo Data chiusura > oggi         (L7<oggi)
			 
		  if (gas_mktime($data_8)<gas_mktime(date("d/m/Y H:i"))){
				$msg.="Data o orario di chiusura gia' passati<br>";
				$e_logical++;

		  }
		  if (gas_mktime($data_8)<gas_mktime($data_7)){
				$msg."Data di chiusura antecedente a quella di apertura<br>";
				$e_logical++;
		  }
		 // if ((gas_mktime($data_8)+ (20*24*60*60)) > (gas_mktime(date("d/m/Y H:i")))){
		//		$msg.="L'ordine può rimanere aperto max 20 giorni<br>";
		//		$e_logical++; 
		//   }
		   if ((gas_mktime($data_7) + 300)<gas_mktime(date("d/m/Y H:i"))){
				$msg.="Data o orario di apertura gia' passati<br>";
				$e_logical++;

		  }
	  
	  
	  
	  
	  
	  
	  $msg.="<br>Verifica i dati immessi e riprova";
	  
	  
	  $e_total = $e_empty + $e_logical + $e_numerical +$e_currency;
	  
	  if($e_total==0){
		 
		 //SWITCH STATO
		
		$data_16=1; // STATO COMUNQUE FUTURO
		
		// if (gas_mktime($data_7)<gas_mktime(date("d/m/Y"),date("H:i:s"))){     
		//			 $data_16=1;
		//		}else{
		//			 $data_16=2; 
		//		}
		// echo $stato;  
		  
		//echo "ZERO ERRORI !!!";
		$data_7 = conv_date_to_db($data_7);
		$data_8 = conv_date_to_db($data_8); 
		
		// QUERY INSERT
		$my_query="INSERT INTO retegas_ordini 
		(id_listini, 
		id_utente, 
		descrizione_ordini, 
		data_chiusura, 
		costo_trasporto, 
		costo_gestione, 
		min_articoli, 
		min_scatola, 
		privato, 
		data_apertura,
		id_stato,
		senza_prezzo,
		mail_level,
		note_ordini)
		VALUES
		('$data_2',
		 '$id_user',
		 '$data_4',
		 '$data_8',
		 '$data_10',
		 '$data_11',
		 '$data_14',
		 '$data_15',
		 '$data_13',
		 '$data_7',
		 '$data_16',
		 '$data_17',
		 '$data_18',
		 '$data_19');";
		
		//INSERT BEGIN ---------------------------------------------------------
		 $result = $db->sql_query($my_query);
		 if (is_null($result)){
			$msg = "Errore nell'inserimento del record";
			include ("../index.php");
			exit;  
		}else{
			
			// se l'ordine è stato inserito, allora inserisco anche le referenze
			$res = mysql_query("SELECT LAST_INSERT_ID();");
			$row = mysql_fetch_array($res);
			$ur=$row[0];
			$gr =id_gas_user($id_user);
			$result = $db->sql_query("INSERT INTO retegas_referenze (id_ordine_referenze, id_utente_referenze, id_gas_referenze) "
									." VALUES ('$ur', '$id_user', '$gr');");
			// e poi vado ad aggiungere anche i gas coinvolti, ma con nessun referente
			
			$box=$_POST["box"];
			while (list ($key,$val) = @each ($box)) { 
			//echo "$val,";
			$result = $db->sql_query("INSERT INTO retegas_referenze (id_ordine_referenze, id_utente_referenze, id_gas_referenze) "
								   ." VALUES ('$ur', '0', '$val');");                        
			}		
			// referenze		
			
			
			$msg = "Nuovo ordine e relative referenze aggiunte<br>
					Appena possibile verrà inoltrata una Mail di avviso<br>
					agli utenti interessati.<br>
					Potrebbe essere necessario qualche minuto prima che questo ordine<br>
					accessibile a tutti.";
			
			
			$nome_ordine = descrizione_ordine_from_id_ordine($ur);
			$messa = "<b>L'utente $fullname ha creato l'ordine $nome_ordine</b>";
			
			
			log_me($ur,$id_user,"ORD","MOD",$messa,0,$my_query);
			
			//include_once ("../cron/cron.php");
			
			//$msg_agg = update_ordini_futuri_aperti();
			
			
			include("../ordini_aperti/ordini_aperti_table.php");
			exit;  
		};
		
		//INSERT END --------------------------------------------------------- 
		
		
		
		 
		  
	  }else{
	  
	  unset($do);    
	  $id=$data_2;
	  include("ordini_form_add.php");  
	  exit;  
		  
	  } // se non ci sono errori
	  
	  } // Se do = add
	  
	  // Controlli sui dati --------------------------------- CCCC

	  
	  // MENU APERTO
	  $menu_aperto=1;
			  
	  // TITOLO FORM_ADD
	  $nl = listino_nome($id);
	  $dn = ditta_nome_from_listino($id);
	  $titolo_tabella="Crea un ordine basato sul listino $nl, ($dn)";
	  
	  
	  
	  
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"30%\"";
	  $col_2="style=\"text-align:left;\""; 
	  
	  // OPZIONI
	  
	  // FORM -------------------------------------------
	  
	  $title_form = "<form name=\"Aggiungi Ordine\" method=\"POST\" action=\"ordini_form_add.php\">";
	  $submit_form ="<input type=\"submit\" value=\"Fai partire questo ordine\" class=\"large green awesome destra\" style=\"text-align:center; margin:20px;\">";  
	  $input_hidden =   "<input type=\"hidden\" name=\"do\"  value=\"add\">";
	  $input_hidden_2 = "<input type=\"hidden\" name=\"data_2\" value=\"$id\">";   //id_ditta
	  
	  
	  // Campi
	  
	  $input_4 = "<input type=\"text\" name=\"data_4\" size=\"28\" value=\"$data_4\">
				  Immettere qua il nome con cui verrà identificato l'ordine."; //descrizione
	  $input_7 = "<input type=\"text\" name=\"data_7\" size=\"20\" value=\"$data_7\" id=\"datepicker_open\">
				  Inserire la data e l'ora in cui l'ordine apre";
	  $input_8 = "<input type=\"text\" name=\"data_8\" size=\"20\" value=\"$data_8\" id=\"datepicker\">
				  Inserire la data in cui l'ordine chiude"; //descrizione
	  $input_10 = "<input type=\"text\" name=\"data_10\" size=\"10\" value=\"$data_10\">
					Il costo (anche approssimativo, si può cambiare anche ad ordine chiuso) del trasporto"; //descrizione
	  $input_11 = "<input type=\"text\" name=\"data_11\" size=\"10\" value=\"$data_11\">
					Idem come sopra, ma è riferito ai costi di gestione"; //descrizione
	  $input_13 = "<input type=\"checkbox\" name=\"data_13\" value=\"$data_13\">
					Se si clicca qui allora l'ordine sarà visibile solo da : $gas_name ; Se lo lasci vuoto
					nella prossima schermata potrai selezionare i GAS che vuoi fare partecipare."; //descrizione
	  $input_14 = "<input type=\"text\" name=\"data_14\" size=\"28\" value=\"$data_14\"> Minimo numero di articoli al di sotto del quale l'ordine non parte."; //descrizione
	  $input_15 = "<input type=\"text\" name=\"data_15\" size=\"28\" value=\"$data_15\"> Minimo numero di scatole al di sotto del quale l'ordine non parte."; //descrizione
	  $input_17 = "<input type=\"checkbox\" name=\"data_17\" size=\"28\" value=\"$data_17\"> 
	  Cliccare qua se si vuole fare partire un ordine conteggiando solo le quantità e non il prezzo."; //descrizione
	  //$input_18 = "<input type=\"text\" name=\"data_18\" size=\"2\" value=\"$data_18\">";
	  $input_18 ='<fieldset class="ui-widget-content">
	  <select name= "data_18">
	  <option value="1" >Normale (Consigliato)</option>
	  <option value="0" >Nessuna comunicazione automatica</option>
	  <option value="2" >Avvisami su ogni cosa che succede</option>
	  </select></fieldset>';
	  $input_19 ='<textarea COLS="28"  name="data_19">'.$data_19.'</textarea>';
	  
	 // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	  $h_table .= " $title_form
					<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div>
					";
	 
	 $h_table .=  "
					<table>
					<tr class=\"odd\">
						<th $col_1>$h4</th>
						<td $col_2>$input_4 $input_hidden $input_hidden_2</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Data Apertura :</th>
						<td $col_2>$input_7</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h8</th>
						<td $col_2>$input_8</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h10</th>
						<td $col_2>$input_10</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h11</th>
						<td $col_2>$input_11</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h14</th>
						<td $col_2>$input_14</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h15</th>
						<td $col_2>$input_15</td>
					</tr>
					
					<tr class=\"odd\">
						<th $col_1>$h18</th>
						<td $col_2>$input_18</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h19</th>
						<td $col_2>$input_19</td>
					</tr>		
					</table>
					<br />
					";

	 // Ho escluso "privato" e "senza prezzo"
	 //<tr class=\"odd\">
	  //                  <th $col_1>$h13</th>
	  //                 <td $col_2>$input_13</td>
	  //              </tr>				
// <tr class=\"odd\">
			 //           <th $col_1>$h17</th>
			  ///          <td $col_2>$input_17</td>
			   //     </tr>					
					
	  // END TABELLA ----------------------------------------------------------------------------
	  
	  
	  // Tabella GAS PARTECIPANTI
	 
	   $titolo_tabella=" Seleziona i GAS esterni che condivideranno quest'ordine.";
	   $h_table .="
					<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div>
					<table>
					";
					
	   $result = $db->sql_query("SELECT * FROM retegas_gas;");             
		while ($row = mysql_fetch_array($result)){
		$riga++;
		$gas = $row[1];
		$id_gas = $row[0];
		$ute = gas_n_user($id_gas);

		if (id_gas_user($id_user)<>$id_gas){  
		$h_table .="    <tr class=\"odd\">
							<th >
								$gas
							</th>
							<td>
								Utenti : $ute
							</td>
							<td>
								<input type=\"checkbox\" name=box[] value=\"$id_gas\"> 
							</td>
							</tr>
		";
		   }
		}
				
	   $h_table .="</table>
				   ";
 
	   $h_table .=  "
					$submit_form
					</form>
					";
	  
	  
	  
	  // HEADER HTML
	  $datetimepicker1 = "#datepicker_open";
	  $datetimepicker2 = "#datepicker";
	  
	  include ("/dini_main.php");
 
}else{
	c1_go_away("?q=no_permission");
} 
?>
