<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
	  
	  // MENU APERTO
	  $menu_aperto=1;
		
	  // QUERY
	  
	  $my_query="SELECT * FROM retegas_listini WHERE  (id_listini='$id') LIMIT 1";
	  
	  // SQL NOMI DEI CAMPI
	  $d1="id_listini";
	  $d2="descrizione_listini";
	  $d3="id_utenti";
	  $d4="id_tipologie";
	  $d5="id_ditte";
	  $d6="data_valido";
	  
			  
	  // INTESTAZIONI CAMPI
	  $h1="ID";
	  $h2="Nome";
	  $h3="Proponente";
	  $h4="Tipologia";
	  $h5="Valido fino al";
	  $h6="Ditta";
	  //$h7="Proponente";      
	  //$h8="Listini associati";
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="";
	  $col_2=""; 


	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = mysql_query($my_query);
	  $row = mysql_fetch_array($result);  
	  
	  //$h_table .= listini_menu_1($id,$id_user);
	 
	 $nuovo_ordine="ON";
	 $operazioni_consentite="ON"; 
	 $nuovi_articoli="ON"; 
	 include("listini_menu_core.php");
		 
		 // VALORI DELLE CELLE da DB---------------------
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = fullname_from_id($row["$d3"]);
			  $c4 = tipologia_nome_from_listino($row["$d1"]);
			  $c5 = conv_date_from_db($row["$d6"]);
			  $c6 = ditta_nome_from_listino($c1);
			  //$c6 = $row["$d6"];
			  //$c7 = fullname_from_id($row["$d7"]);
			  //$c8 = listini_ditte($c1);
			  
			 	 // VALORI CELLE CALCOLATE ----------------------      

		 // TITOLO TABELLA
$titolo_tabella="<h3>Listino cod. $c1 ditta $c6</h3>";
		 
		 
		 
$h_table .= "<div class=\"ui-widget-content ui-corner-all padding_6px\" style=\"margin-bottom:6px;\">$titolo_tabella<br>

			 <table>
			 <tr>
			 <td cellpadding=\"0\" style=\"padding:0px\">";		 
$h_table .=  "<table  cellpadding=\"0\" style=\"padding:0px\">
		<tr class=\"odd\">
			<th>$h1</th>
			<td>$c1</td>
		</tr>
		<tr  class=\"odd\">
			<th>$h2</th>
			<td>$c2</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h3</th>
			<td $col_2>$c3</td>
		</tr>
		</table>
		</td>
		
		<td  style=\"padding:0px\">
		<table cellpadding=\"0\" style=\"padding:0px\">        
		
		<tr class=\"odd\">
			<th $col_1>$h4</th>
			<td $col_2>$c4</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h5</th>
			<td $col_2>$c5</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h6</th>
			<td $col_2>$h6</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		
		<br />";

	  // END TABELLA LISTINI -----------------------------------------------------------------------

// --------------START LISTINI
	  // UPLOAD
	  
	 if($tipo_file=="CSV"){$titolo_file="Carica una tabella di testo <b>(.CSV)</b> contenente gli articoli per questo listino";
						   $destinazione = "listini_upload.php";
						   $avvertenze = "<h3>Attenzione : Gli articoli RAGGRUPPATI si possono caricare (per ora) soltanto usando un file EXCEL</h3>
                                          La prima riga è destinata ai nomi delle colonne, pertanto non sar? inclusa dall'importazione.<br>
										  Alcuni fogli elettronici esportano i file CSV usando dei delimitatori di campo diversi da quelli standard.<br>
										  Se il file non viene correttamente interpretato provate a sostituirli.<br>
										  Qua trovate un file CSV di esempio. <a class=\"silver awesome small\" href=\"../../images/listino_di_prova_CSV.csv\">Listino_esempio_CSV.csv</a><br>";
										  
						   $tag_input = "Carica una tabella di testo .csv : <input type=\"file\" name=\"upfile\" class=\"\">";
						   $tag_separatori = 'Separatore di elenco  <input type="text" name="separatore" value=";" size="1" maxlength="1"   title="separatore">
						   <span style="font-size:0.8em; font-weight:normal;">Normalmente ? il carattere ";" (Punto e virgola). In alcuni casi p?o essere la "," (virgola)</span><br>
						   </span><br><br>';};
	 if($tipo_file=="XLS"){$titolo_file="Carica un file MS EXCEL <b>(.XLS)</b> contenente gli articoli per questo listino";
						   $destinazione = "listini_upload_xls.php";
						   $avvertenze = "La prima riga è destinata ai nomi delle colonne, pertanto non sarà inclusa dall'importazione.<br>
										  L'importazione non è stata testata con tutte le versioni esistenti di excel, pertanto non se ne garantisce il successo.";                             
						   $tag_input = "Carica un file EXCEL : <input type=\"file\" name=\"upfile\">";};
	 if($tipo_file=="GOO"){$titolo_file="Incolla qua sotto l'indirizzo di una tabella online creata con <b>GOOGLE DOCS</b> contenente gli articoli per questo listino";
						   $destinazione = "listini_upload_goo.php";
						   $avvertenze = "<h3>Attenzione : Gli articoli RAGGRUPPATI si possono caricare (per ora) soltanto usando un file EXCEL</h3>
                                          La prima riga è destinata ai nomi delle colonne, pertanto non sarà inclusa dall'importazione.<br>
										  <b>Per poter essere importato, il documento GOOGLE deve essere prima \"Pubblicato sul web\".</b><br>
										  Tra una pubblicazione e la sua effettiva visibilità possono trascorrere alcuni minuti.<br>
										  Il servizio si appoggia su GoogleDocs.<br>
										  Qua trovate un link ad un listino pubblico di esempio. <a class=\"silver awesome small\" href=\"https://spreadsheets.google.com/ccc?key=0An0LoUdzBJs0dEhVV3UtTDNkTzVvazd2NlBhQ1JUR1E&hl=it\">Documento_esempio_Google</a><br>";
								
						   $tag_input = "Link ad un GOOGLE DOC : <input type=\"text\"  size=\"50\" name=\"upfile\">";};                      
	 
	 $n_articoli_esistenti = articoli_n_in_listino($c1); 
	 if($n_articoli_esistenti>0){$alert_articoli='<div class="ui-state-error ui-corner-all padding_6px mb6">
													Questo listino contiene gi? '.$n_articoli_esistenti.' articoli. Quelli che tu inserisci ora verranno ACCODATI a quelli esistenti</div><br>';}
	 
	  
		  
	  $h_table .= " $alert_articoli
					$titolo_file
					<br />
					$avvertenze
					<br />
					<form action=\"$destinazione\" method=\"post\" enctype=\"multipart/form-data\">
									$tag_separatori 
									$tag_input
									<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000\">
									<input type=\"hidden\" name=\"listino\" value=$id>
									<input type=\"hidden\" name=\"tipo_file\" value=$tipo_file>
									<input type=\"submit\" class=\"awesome green\" value=\"Carica il file\">
									</form>
									</div>
									
			";
  


 $msg = "ATTENZIONE <br> Il listino viene importato a partire dalla SECONDA RIGA<br>
		 La prima riga è usata per i nomi delle colonne.";
	  
 include ("listini_main.php");
 
 
}else{
	c1_go_away("?q=no_permission");
} 
?>