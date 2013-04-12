<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$permission = $cookie_read[6];
		
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	
	
	  
	  // Controlli sui dati --------------------------------- CCCC
	  
	  // PULIZIA CAMPI
	  
	  //$data_1 = puliscistringa($data_1);
	  //$data_2 = puliscistringa($data_2);
	  //$data_3 = puliscistringa($data_3);
	  //$data_4 = puliscistringa($data_4);
	  
	  
	  //DEBUG
	  //echo "Data_1:(".$data_1.") ";
	  //echo "Data_2:(".$data_2.") ";
	  //echo "Data_3:(".$data_3.") <BR>";
	  //echo "Data_4:(".$data_4.") ";
	  //echo "Data_5:(".$data_5.") ";
	  //echo "Data_6:(".$data_6.") <BR>";
	  //DEBUG
	 
	 
	  // COntrollo se posso
	   if(!($permission & perm::puo_creare_listini)){
			$q = "not_allowed";
			include ("../index.php");
			exit;            
	  } 
	  
	  
	  
	  
	  if ($do=="add"){
	  
		  
	  (int)$data_7;	  
		  
	  // se è vuoto
	  if (empty($data_2)){$msg.="Devi almeno inserire il nome del listino<br>";$e_empty++;};
	  if (empty($data_3)){$msg.="Devi associare una tipologia di merce<br>";$e_empty++;};
	  if (empty($data_4)){$msg.="Devi inserire la data di scadenza<br>";$e_empty++;};
	  //if (empty($data_2)){$msg.="Il campo 2 non può essere vuoto<br>";$e_empty++;};
	  //if (empty($data_3)){$msg.="Il campo 3 non può essere vuoto<br>";$e_empty++;};
	  
	  // data di scadenza maggiore di oggi
	  if ($data_2){};
	  if ($data_3){};
	  if (!controllodata($data_4)){
		$e_logical ++;
		$msg.="Formato della data non riconosciuto<br>";	
	  };
	  
	  //SE E' SCADUTO
	  if (gas_mktime($data_4,null)<gas_mktime(date("d/m/Y"),date("H:i:s"))){
	  $msg.="Data antecedente ad oggi<br>";
	  $e_logical ++;             
	  }

	  
	  
	  
	  
	  
	  
	  $msg.="<br>Verifica i dati immessi e riprova";
	  
	  
	  $e_total = $e_empty + $e_logical + $e_numerical;
	  
	  if($e_total==0){
		//echo "ZERO ERRORI !!!";
		$data_4 = conv_date_to_db($data_4);
		// QUERY INSERT
		$my_query="INSERT INTO retegas_listini 
				(descrizione_listini,
				 id_tipologie,
				 data_valido,
				 id_ditte,
				 id_utenti,
				 tipo_listino,
                 is_privato) VALUES (
				 '$data_2',
				 '$data_3',
				 '$data_4',
				 '$data_5',
				 '$id_user',
				 '$data_6',
                 '$data_7');";
		
		//INSERT BEGIN ---------------------------------------------------------
		 $result = $db->sql_query($my_query);
		 if (is_null($result)){
			$msg = "Errore nell'inserimento del record";
			include ("../index.php");
			exit;  
		}else{
			$nome_ditta = ditta_nome($data_5);
			log_me(0,$id_user,"LIS","ADD","Creato il listino ($data_2) riferito alla ditta ($nome_ditta)",0,$my_query);
			$msg = "Nuovo listino aggiunto";
			$id = $data_5;
			include("../ditte/ditte_form.php");
			exit;  
		};
		
		//INSERT END --------------------------------------------------------- 
		
		
		
		 
		  
	  }else{
	  
	  unset($do);    
	  $id=$data_5;
	  include("listini_form_add.php");  
	  exit;  
		  
	  } // se non ci sono errori
	  
	  } // Se do = add
	  
	  // Controlli sui dati --------------------------------- CCCC

	  
	  // MENU APERTO
	  $menu_aperto=1;
			  
	  // TITOLO FORM_ADD
	  $nd = ditta_nome($id);
	  $titolo_tabella="Aggiungi una nuovo listino alla ditta \"$nd\"";
	  
	  // INTESTAZIONI CAMPI
	  
	  $h2="Nome";
	  $h3="Tipologia merce";
	  $h4="Data scadenza listino";
	  
	  
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"30%\"";
	  $col_2=""; 
	  
	  // OPZIONI
	  
	  // FORM -------------------------------------------
	  
	  $title_form = "<form name=\"Aggiungi Listino\" method=\"POST\" action=\"listini_form_add.php\">";
	  $submit_form ="<input type=\"submit\" value=\"Aggiungi\" class=\"large green awesome\" style=\"margin:20px\">";  
	  
	  // Campi
	  
	  $input_2 = "<input type=\"text\" name=\"data_2\" size=\"28\" value=\"$data_2\">"; //descrizione
	  //$input_3 = "<input type=\"text\" name=\"data_3\" size=\"28\" value=\"$data_3\">"; //tipologia
	  $input_3 = "<fieldset> <select name= \"data_3\"> ";
	  $input_3 .= "<option value=\"0\">Selezionare Tipologia merce</option> ";
	  $result = mysql_query("SELECT * FROM retegas_tipologia");
		while ($row = mysql_fetch_array($result)){
				$T1 = $row[0];
				$T2 = $row[1];
	  $input_3 .= "<option value=\"".$T1 ."\">".$T2 ."  </option>";   
		 }//end while
	  $input_3.="</select>
		</fieldset>
		";
	  //echo "D4". $data_4;  
	  $input_4 = "<input type=\"text\" name=\"data_4\" size=\"28\" value=\"$data_4\" id=\"datetimepicker\">"; //valido

	  $input_hidden =   "<input type=\"hidden\" name=\"do\"  value=\"add\">";
	  $input_hidden_2 = "<input type=\"hidden\" name=\"data_5\" value=\"$id\">";   //id_ditta
      
	  $input_6 = "<fieldset> <select name= \"data_6\"> ";
	  $input_6 .= "<option value=\"0\">Listino Normale</option> ";
	  $input_6 .= "<option value=\"1\">Listino Magazzino</option>";     
	  $input_6 .="</select>
		</fieldset>
		";
      $input_7 = "<fieldset> <select name= \"data_7\"> ";
      $input_7 .= "<option value=\"0\">Pubblico (tutti i gas)</option> ";
      $input_7 .= "<option value=\"1\">Privato (solo per il tuo gas)</option>";     
      $input_7 .="</select>
        </fieldset>
        ";  
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	  $h_table .= "
					<div class=\"ui-widget-header ui-corner-all padding_6px\">
					<h3>$titolo_tabella</h3>
					 $title_form 
					<table>
					";
	 
	 $h_table .=  "
					<tr class=\"odd\">
						<th $col_1>$h2</th>
						<td $col_2>$input_2</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h3</th>
						<td $col_2>$input_3</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Tipo listino</th>
						<td $col_2>$input_6</td>
					</tr>
                    <tr class=\"odd\">
                        <th $col_1>Visibilità</th>
                        <td $col_2>$input_7</td>
                    </tr>
					<tr class=\"odd\">
						<th $col_1>$h4</th>
						<td $col_2>$input_4 $input_hidden $input_hidden_2</td>
					</tr>
					</table>
					$submit_form
					</form>
					</div>";

	  // END TABELLA ----------------------------------------------------------------------------
	  
	  // HEADER HTML
	  $datepickers="ON";
	  $datepickers_name_1 = "datetimepicker";
	  include ("listini_main.php");
 
}else{
	c1_go_away("?q=no_permission");
} 
?>
