<?php
  if (eregi("oc_art_ord_crono_table.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START articoli

	  
	  $titolo_tabella="Cronologia articolo $id_art, ordine $id";
	  
	 
	
	  // QUERY LISTINI
	  $my_query="SELECT
					retegas_dettaglio_ordini.data_inserimento,
					maaking_users.id_gas,
					maaking_users.userid,
					retegas_dettaglio_ordini.data_inserimento as pippo,
					retegas_dettaglio_ordini.qta_ord,
					retegas_dettaglio_ordini.qta_arr,
					retegas_articoli.codice,
					retegas_articoli.descrizione_articoli,
					maaking_users.fullname
					FROM
					retegas_dettaglio_ordini
					Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
					Inner Join maaking_users ON maaking_users.userid = retegas_dettaglio_ordini.id_utenti
					WHERE
					retegas_dettaglio_ordini.id_ordine =  '$id' AND
					retegas_dettaglio_ordini.id_articoli =  '$id_art'
					ORDER BY
					retegas_dettaglio_ordini.data_inserimento DESC";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br /> 
			<div class=\"ui-widget-header ui-corner-all padding_6px\" style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th class=\"$col_1\">Utente</th>
			<th class=\"$col_2\">Quantità ordinata</th>
			<th class=\"$col_2\">Quantità disponibile</th>
			<th class=\"$col_3\">Data e ora ordine</th>
		</tr>";
  
	   $riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){

		   
		   
			  $riga++;    
			  $cN = $row["fullname"];
			  $c2 = $row["qta_ord"];
			  $c3 = conv_datetime_from_db($row["pippo"]);
			  $c4 = $row["qta_arr"];
			  $cG = $row["id_gas"]; 
			  $gN = gas_nome($cG);
			
			// if ($cG<>$gas){
			//	 $cN = "XXXXXXXXXX - ($gN)";   
			//	}else{
				 $cN .= " - ($gN)";   
			//	}
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		
		
		$h_table.= "<td $col_1><a href=\"../../utenti/utenti_form.php?id=".$row["userid"]."\">$cN</a></td> 
					<td $col_2>$c2</td>
					<td $col_3>$c4</td>    
					<td $col_4>$c3</td>

				</tr>
			";
			
		   
		 }//end while
		 
		 
	 
		 
		 $h_table.= "</table>
					 
		 ";





?>
