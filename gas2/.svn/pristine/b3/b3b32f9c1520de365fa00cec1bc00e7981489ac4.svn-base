<?php
  if (eregi("oa_art_ord.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START articoli
	  // TITOLO TABELLA
	  //$nome_listino=listino_nome($id);
	  
	  
	  $titolo_tabella="<div class=\"padding-6px;\">Dettaglio articoli</div> <br>
	  <div class=\"ui-state-error ui-corner-all padding_6px mb6\">Finchè l'ordine non sarà chiuso, questa tabella è da considerarsi PROVVISORIA</div>
	  <div class=\"padding-6px\" style=\"font-size:0.8em\">Clicca sulle intestazioni delle colonne per raggruppare/ordinare i dati</div>";
	  
	 
	  if(isset($is_pdf)){
		  include("oa_art_dett_formatting_pdf.php");
	  }else{
		  include("oa_art_dett_formatting_screen.php");
	  }
	  
	  
	  
	  // TOOLTIPS

	  
	  


	  
	  
	  // QUERY LISTINI
	  $my_query="SELECT
Sum(retegas_dettaglio_ordini.qta_ord) as sum_q_o,
retegas_articoli.codice,
retegas_articoli.id_articoli,
retegas_articoli.descrizione_articoli,
maaking_users.fullname,
maaking_users.id_gas,
retegas_articoli.qta_scatola,
retegas_articoli.qta_minima,
retegas_gas.descrizione_gas,
retegas_dettaglio_ordini.id_utenti
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
WHERE
retegas_dettaglio_ordini.id_ordine =  '$id'
GROUP BY
retegas_dettaglio_ordini.id_utenti,
retegas_dettaglio_ordini.id_articoli";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br />
			<div  class=\"ui-widget-header ui-corner-all padding_6px\"> 
			<div  style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table id=\"$table_group_name\" class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			<thead>
		<tr>
			<th>Utente</th>
			<th>GAS</th>
			<th>Codice</th>
			<th>Descrizione</th>
			<th>Q.tà Ord.</th>
		</tr>
		</thead>
		<tbody>";
  
	   $riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){

		   
		   
			  $riga++;
			  
			  if($row["id_gas"]==$gas){
					$c1 = $row["fullname"];
			  }else{
					$c1 = fullname_ref_gas_ordine($id,$row["id_gas"]).", [R.G]";
			  }

			  $c2 = $row["descrizione_gas"];
			  $c3 = $row["codice"];
			  $c4 = $row["descrizione_articoli"];
			  $c5 = $row["sum_q_o"];
		   
			  
			  $id_art = $row["id_articoli"];
			  $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		
		
		$h_table.= "<td>$c1</td> 
					<td>$c2</td>    
					<td>$c3</td>
					<td>$c4</td>
					<td>$c5</td>
				</tr>
			";
			
		   
		 }//end while
		 
		 
	 
		 
		 $h_table.= "   </tbody>
						</table>
						</div>
		 ";





?>
