<?php
  if (eregi("mia_spesa.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../index.php"); die();
}
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	  $numero_articoli_in_listino = articoli_n_in_listino($id);
	  
	  $titolo_tabella="La mia spesa (dettaglio e assegnazione amici)";
	  
	 

	  
	  
	  
	  // TOOLTIPS

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"4%\" style=\"vertical-align:middle\" ";    //opzioni    
	  $col_2="width=\"8%\" class=\"gas_c1\"";
	  $col_3="width=\"10%\" class=\"gas_c1\"";
	  $col_4="width=\"*\" class=\"gas_c1\" style=\"text-align:left;\"";
	  $col_5="width=\"10%\" class=\"gas_c1\"";
	  $col_6="width=\"4%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_7="width=\"4%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_8="width=\"5%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_9="width=\"5%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_10="width=\"5%\" class=\"gas_c2\" style=\"text-align:right;\"";  
	  

	  // INTESTAZIONI
	  
	  $h1="Opzioni";      
	  $h2="Assegnazione";
	  $h3="Articolo";      
	  $h4="Descrizione";
	  $h5="Quantità";
	  $h6="Prezzo";
	  $h7="Totale Articolo";
	  $h8="Subtotale Amico";
	  $h9="Costi"; 
	  $h10="Totale"; 
	  
	  
	  
	  // QUERY LISTINI
	  $my_query="SELECT
					retegas_ordini.id_ordini,
					retegas_ordini.descrizione_ordini,
					retegas_dettaglio_ordini.id_utenti,
					retegas_distribuzione_spesa.id_amico,
					retegas_amici.nome,
					retegas_dettaglio_ordini.id_articoli,
					retegas_articoli.descrizione_articoli,
					retegas_articoli.codice,
					retegas_distribuzione_spesa.qta_ord,
					retegas_articoli.prezzo,
					retegas_articoli.qta_minima,
					retegas_dettaglio_ordini.id_dettaglio_ordini,
					retegas_articoli.articoli_unico
					FROM
					retegas_ordini
					Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
					Inner Join retegas_distribuzione_spesa ON retegas_dettaglio_ordini.id_dettaglio_ordini = retegas_distribuzione_spesa.id_riga_dettaglio_ordine
					Left Join retegas_amici ON retegas_distribuzione_spesa.id_amico = retegas_amici.id_amici
					Inner Join retegas_articoli ON retegas_articoli.id_articoli = retegas_dettaglio_ordini.id_articoli
					WHERE
					retegas_ordini.id_ordini =  '$id' AND
					retegas_dettaglio_ordini.id_utenti =  '$id_user'
					ORDER BY 
					retegas_amici.nome";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br> 
			<div class=\"ui-widget-header ui-corner-all padding_6px\">
			<h3>$titolo_tabella</h3>

			<table class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th>$h1</th>
			<th>$h2</th>
			<th>$h3</th>
			<th>$h4</th>
			<th>$h5</th>
			<th>$h6</th>
			<th>$h7</th>
			
			<th>$h9</th>
			<th>$h10</th>         
		</tr>";
  
	   $riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){
			if (($old_amico<>$row["id_amico"]) && $riga>0){
				
				include("mia_spesa_subtotal.php");	
				
				
				
			}  
		   
		   
		   $riga++;
			  $c1 = "";
			  $c2 = $row["nome"];
			  if(empty($c2)){		  
					   $c2  ="Me stesso";
			  }
			  $c3 = $row["codice"];
			  if ($row["articoli_unico"]==1){
				  
			  }
			  
			  $c4 = $row["descrizione_articoli"];
			  $c5 = $row["qta_ord"];
			  $c6 = $row["prezzo"];              
			  $c7 = number_format((float)round($c5*$c6,2),2,",","");
			  $c8 = "";
			  $c9 = "";
			  $c10 = "";
			  
			  $subt_amico = round(valore_netto_singolo_amico($row["id_ordini"],$id_user,$row["id_amico"]),2); 
			  $subt_nome_amico = $c2;
			  
			  $c5 = (float)$c5;
              $c6 = number_format($c6,2,",","");
              
			  $id_art= $row["id_articoli"];// ID articolo
			  if ($row["articoli_unico"]<>1){ 
				$c1 = "<a class=\"option yellow awesome\" title=\"Modifica\" href=\"../ordini_aperti_mod_q.php?id=$id_art&id_ordine=$id&q_min=".$row["qta_minima"]."\">M</a>";
			  }else{
				$c1 = "<a class=\"option orange awesome\" title=\"Modifica\" href=\"../ordini_aperti_mod_q.php?id=$id_art&id_ordine=$id&q_min=".$row["qta_minima"]."&id_dett=".$row["id_dettaglio_ordini"]."\">M</a>";		  
			  }
			  //$c1 .= "<a class=\"option red awesome\" title=\"Cancella\" href=\"../articoli/articoli_form_delete.php?id=$c10\">C</a>";  
	
			  
			  
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2>$c2</td>    
					<td $col_3>$c3</td>
					<td $col_4>$c4</td>
					<td $col_5>$c5</td>
					<td $col_6>$c6</td>
					<td $col_7>$c7</td>				
					<td $col_8>$c9</td>
					<td $col_9>$c10</td>  
				</tr>
			";
			
		 $old_amico = $row["id_amico"];   
		 }//end while
		 
		 
		 include("mia_spesa_subtotal.php");
		 
		 
		 $totale = (float)round(valore_totale_mio_ordine($id,$id_user),2);
		 include("mia_spesa_total.php");    
		 
		 
		 $h_table.= "</table>
					 </div>
		 ";





?> 