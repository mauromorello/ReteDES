<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	
		  // se non è una ditta associato a lui
	 if(empty($data_1)){
			$data_1=$id;
	 }     
	 
		 //echo $id_user . "-->" .ditta_user($data_1);
	 if($id_user<>listino_proprietario($data_1)){
		 $msg = "Listino non inserito da te. Impossibile modificare";
		 include("listini_table_miei.php");
		 exit;
	 }
	  
	  // MENU APERTO
	  $menu_aperto=1;
	
	  // QUERY VISUAL
	  $query_visual = "SELECT * FROM retegas_listini WHERE  (id_listini='$id') LIMIT 1";
	  
	  // TITOLO FORM_ADD
	  $titolo_tabella="Modifica i dati di questa scheda";
	  
	  // INTESTAZIONI CAMPI
	  $h1="ID";
	  $h2="Nome";
	  $h3="Proprietario";
	  $h4="Tipologia";
	  $h5="Ditta";
	  $h6="Valido fino al";

	  
	  // SQL NOMI DEI CAMPI
	  $d1="id_listini";
	  $d2="descrizione_listini";
	  $d3="id_utenti";
	  $d4="id_tipologie";
	  $d5="id_ditte";
	  $d6="data_valido";
						   
	  
	  $result = mysql_query($query_visual);
	  $row = mysql_fetch_array($result);                    
	  
	  // VALORI DELLE CELLE da DB---------------------
	  $c1 = $row["$d1"];
	  $c2 = $row["$d2"];
	  $c3 = $row["$d3"];
	  $c4 = $row["$d4"];
	  $c5 = $row["$d5"];
	  $c6 = conv_only_date_from_db($row["$d6"]);
	  $c7 = $row["tipo_listino"];
	 
	  
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
	  //DEBUG
	  
	  
	  
	  
	  
if ($do=="mod"){
	  //echo "EVALUTATE <br>";
	  
	   // se è vuoto
	  if (empty($data_2)){$msg.="Devi almeno inserire il nome del listino<br>";$e_empty++;};
	  if (empty($data_4)){$msg.="Devi associare una tipologia di merce<br>";$e_empty++;};
	  if (empty($data_6)){$msg.="Devi inserire la data di scadenza<br>";$e_empty++;};
	  //if (empty($data_2)){$msg.="Il campo 2 non può essere vuoto<br>";$e_empty++;};
	  //if (empty($data_3)){$msg.="Il campo 3 non può essere vuoto<br>";$e_empty++;};
	  
	  // data di scadenza maggiore di oggi
	
	  if (!controllodata($data_6)){
		$e_logical ++;
		$msg.="Formato della data non riconosciuto<br>";    
	  };
	  
	  //SE E' SCADUTO
	  if (gas_mktime($data_6,null)<gas_mktime(date("d/m/Y"),date("H:i:s"))){
	  $msg.="Data antecedente ad oggi<br>";
	  $e_logical ++;             
	  }
	  
	  
	  $msg.="<br>Verifica i dati immessi e riprova";
	  
	  
	  $e_total = $e_empty + $e_logical + $e_numerical;
	  
	  if($e_total==0){

	  
	  
	  $data_6 = conv_date_to_db($data_6);
		// QUERY EDIT
		$sql = "UPDATE retegas_listini 
			  SET 
			  retegas_listini.$d2 = '$data_2',
			  retegas_listini.$d4 = '$data_4',
			  retegas_listini.$d6 = '$data_6',
			  retegas_listini.tipo_listino = '$data_7' 
			  WHERE 
			  retegas_listini.$d1 = '$data_1' LIMIT 1;";
			  
		$result = $db->sql_query($sql);
		//echo $result;
		//EDIT BEGIN ---------------------------------------------------------
		 
		 if (is_null($result)){
			$msg = "Errore nella modifica dei dati";
			include ("../index.php");
			exit;  
		}else{
			
			$msg = "Dati modificati";
			$id=$data_1;
			unset($do);
			
			include("listini_form.php");
			exit;  
		};
		
		//EDIT END --------------------------------------------------------- 
		
		
		unset($do);
		
		$id=$data_1;
		echo $id;
		include("listini_form_edit.php");
		exit; 
		  
	  } // se non ci sono errori
	  
}else{
	$data_1=$c1;
	$data_2=$c2;
	$data_3=$c3;
	$data_4=$c4;
	$data_5=$c5;
	$data_6=$c6;
	$data_7=$c7;
}	  
	  
	  // Controlli sui dati --------------------------------- CCCC

	  
	  // MENU APERTO
	  $menu_aperto=1;
			  
	  // TITOLO FORM_ADD
	  $titolo_tabella="Modifica i dati di questo listino";
	  
	  
	  
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"30%\"";
	  $col_2=""; 
	  
	  // OPZIONI
	  
	  // FORM -------------------------------------------
	  
	  $title_form = "<form name=\"Modifica listino\" method=\"POST\" action=\"listini_form_edit.php\">";
	  $submit_form ="<input class=\"large green awesome destra\" style=\"margin:20px;\" type=\"submit\" value=\"Modifica\">";  
	  
	  // Campi
	  $input_1 = "<input type=\"text\" name=\"data_1\" size=\"48\" value=\"$data_1\">";
	  $input_2 = "<input type=\"text\" name=\"data_2\" size=\"48\" value=\"$data_2\">";
	  $input_3 = "<input type=\"text\" name=\"data_3\" size=\"48\" value=\"$data_3\">";
	  
	  // input 4
	  $input_4 =" <fieldset> <select name= \"data_4\"> ";
		
		$result = mysql_query("SELECT * FROM retegas_tipologia");
		$totalrows = mysql_num_rows($result);
		while ($rows = mysql_fetch_array($result)){
				$idtip = $rows[0];
				$descrizionetip = $rows[1];
				if ($idtip == $data_4){
					$input_4 .= "<option value=\"".$idtip ."\" selected=\"selected\">".$descrizionetip ."  </option>";
				}else{
					$input_4 .= "<option value=\"".$idtip ."\">".$descrizionetip ."  </option>"; 
				}
		 }//end while
	  $input_4 .="</select>
		</fieldset>";
	  
	  // input 4
	  
	  $input_5 = "<input type=\"text\" name=\"data_5\" size=\"48\" value=\"$data_5\">";
	  $input_6 = "<input type=\"text\" name=\"data_6\" size=\"48\" value=\"$data_6\" id=\"datepicker\">";
	  $input_7 = "<input type=\"text\" name=\"data_7\" size=\"48\" value=\"$data_7\">";
	  
	  $input_hidden2 = "<input type=\"hidden\" name=\"data_1\"  value=\"$data_1\">";
	  $input_hidden = "<input type=\"hidden\" name=\"do\"  value=\"mod\">";
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	  $h_table .= " <div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div>
					
					$title_form
					<table>
					";
	 
	 $h_table .=  "
					<tr class=\"odd\">
						<th $col_1>$h1</th>
						<td $col_2>$data_1</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h2</th>
						<td $col_2>$input_2</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h4</th>
						<td $col_2>$input_4</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>Tipo (0=normale; 1=magazzino)</th>
						<td $col_2>$input_7</td>
					</tr>
					<tr class=\"odd\">
						<th $col_1>$h6</th>
						<td $col_2>$input_6 $input_hidden $input_hidden2</td>
					</tr>
					</table>
					$submit_form
					</form>";

	  // END TABELLA ----------------------------------------------------------------------------
	  
	  // HEADER HTML
	  include ("listini_main.php");
 
}else{
	c1_go_away("?q=no_permission");
} 
?>
