<?php
  if (eregi("oc_mia_spesa.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	  $numero_articoli_in_listino = articoli_n_in_listino($id);
	  
	  $titolo_tabella="La mia spesa (dettaglio e assegnazione articoli agli amici)";
	  
	 
	  if(isset($is_pdf)){
		  include("oc_mia_spesa_formatting_pdf.php");
	  }else{
		  include("oc_mia_spesa_formatting_screen.php");
	  }
	  
	  
	  
	  // TOOLTIPS

	  
	  


	  
	  
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
					retegas_dettaglio_ordini.id_dettaglio_ordini,
					retegas_distribuzione_spesa.qta_arr,
					retegas_articoli.u_misura,
					retegas_articoli.misura,
					retegas_articoli.qta_minima 
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
					retegas_amici.nome
					";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br /> 
			<div class=\"ui-widget-header ui-corner-all padding_6px\" style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th class=\"$col_1\">$h1</th>
			<th class=\"$col_2\">$h2</th>
			<th class=\"$col_3\">$h3</th>
			<th class=\"$col_4\">$h4</th>
			<th class=\"$col_5\">$h5</th>
			<th class=\"$col_6\">$h6</th>
			<th class=\"$col_7\">$h7</th>
			<th class=\"$col_8\">$h8</th>
			
			<th class=\"$col_10\">$h10</th>
			<th class=\"$col_11\">$h11</th>         
		</tr>";
  
	   $riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){
		   
		   if (($old_amico<>$row["id_amico"]) && $riga>0){
				
				include("oc_mia_spesa_subtotal.php");	
				
				
				
			}  
		   
		   
			  $riga++;    
			  $c1 = "";
			  $c2 = $row["nome"];
			  if(empty($c2)){		  
					   $c2  ="Me stesso";
			  }
			  $c3 = $row["codice"];
			  $c4 = $row["descrizione_articoli"];
			  $c5 = round($row["qta_ord"],2);
			  $c6 = round($row["qta_arr"],2);             
			  $c7 = $row["prezzo"]; 
			  $c8 = round($c6*$c7,2); 
			  $c9 = "";
			  $c10 = "";
			  $c11 = "";
			  
			  $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
			  //echo "--id_ord: ". $row["id_ordini"];
			  //echo "--id_amico: ". $row["id_amico"];
			  //echo "--id user: ". $id_user."<br>";  
			  
			  $subt_amico = valore_netto_singolo_amico($row["id_ordini"],$id_user,$row["id_amico"]); 
			  //echo "SUBT AMICO ". $subt_amico."<br>";
			  $subt_nome_amico = $c2;
			  
			  
			  $id_art= $row["id_articoli"];// ID articolo
			  
			  if(isset($is_pdf)){
				   $c1 = "";  
				}else{
				 $c1 = "<a class=\"option blue awesome\" title=\"Assegna\" href=\"../ordini_chiusi_ass_q.php?id=$id_art&id_ordine=$id&q_min=".$row["qta_minima"]."&id_dett=".$row["id_dettaglio_ordini"]."\">A</a>";
			  
				 
			  }
			  //$c1 .= "<a class=\"option red awesome\" title=\"Cancella\" href=\"../articoli/articoli_form_delete.php?id=$c10\">C</a>";  
	
			   if($c5<>$c6){
				if(($c6==0) or (empty($c6))){
				  $warning = "<div class=\"campo_alert\">ANNULLATA</div>";
				  }else{
				  $warning = "<div class=\"campo_alert\">MODIFICATA</div>";  
				}   
			   }else{
					$warning = "";    
			   }
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		$c7 = number_format($c7,2,",","");
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2>$c2</td>    
					<td $col_3><a title=\"Codice gas:".$row["id_articoli"]."\">$c3 (".$row["id_dettaglio_ordini"].")</s></td>
					<td $col_4>$c4 $misu</td>
					<td $col_5>$c5</td>
					<td $col_6>$c6<br>$warning</td>
					<td $col_7>$c7 $euro</td>
					<td $col_8>$c8 $euro</td>
					
					<td $col_10>$c10</td>
					<td $col_11>$c11</td>  
				</tr>
			";
			
		 $old_amico = $row["id_amico"];   
		 }//end while
		 
		 
		 include("oc_mia_spesa_subtotal.php");
		 
		 
		 $totale = round(valore_totale_mio_ordine($id,$id_user),2);
		 include("oc_mia_spesa_total.php");    
		 
		 
		 $h_table.= "</table>
					 
		 ";





?>