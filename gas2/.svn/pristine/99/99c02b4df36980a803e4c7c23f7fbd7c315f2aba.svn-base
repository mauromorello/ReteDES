<?php
  if (eregi("oa_art_ord.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START articoli

	  
	  $titolo_tabella="Raggruppamento articoli <br><div class=\"ui-state-error ui-corner-all padding_6px\">  Finchè l'ordine non sarà chiuso, questa tabella è da considerarsi PROVVISORIA</div>";
	  
	 
	  if(isset($is_pdf)){
		  include("oa_art_ord_formatting_pdf.php");
	  }else{
		  include("oa_art_ord_formatting_screen.php");
	  }
	  
	  
	  
	  // TOOLTIPS

	  
	  


	  
	  
	  // QUERY LISTINI
	  $my_query="SELECT
			retegas_articoli.id_articoli, 
			retegas_articoli.codice,
			retegas_dettaglio_ordini.id_ordine,
			retegas_articoli.prezzo,
			retegas_articoli.descrizione_articoli,
			format(Sum(retegas_articoli.prezzo*retegas_dettaglio_ordini.qta_ord),2) AS tot,
			Count(retegas_dettaglio_ordini.id_ordine) AS qOrd,
			retegas_articoli.qta_scatola,
			format(Sum(retegas_dettaglio_ordini.qta_ord),2) AS Somma_qta,
			(sum(retegas_dettaglio_ordini.qta_ord) DIV retegas_articoli.qta_scatola) AS Scatole_intere,
			(sum(retegas_dettaglio_ordini.qta_ord) MOD retegas_articoli.qta_scatola) AS Avanzo,
			retegas_ordini.min_articoli,
			retegas_ordini.min_scatola,
			retegas_articoli.id_articoli,
			retegas_articoli.u_misura,
			retegas_articoli.misura
			FROM
			retegas_dettaglio_ordini
			Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
			Inner Join retegas_ordini ON retegas_dettaglio_ordini.id_ordine = retegas_ordini.id_ordini
			WHERE
			retegas_dettaglio_ordini.id_ordine =  '$id'
			GROUP BY
			retegas_articoli.codice,
			retegas_articoli.id_articoli, 
			retegas_articoli.descrizione_articoli
			ORDER BY
			retegas_articoli.codice;";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br />
			<div  class=\"ui-widget-header ui-corner-all padding_6px\"> 
			<div  style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table id=\"oa_art_ord\" class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			<thead>
		<tr>
			<th class=\"$col_1\">$h1</th>
			<th class=\"$col_2\">$h2</th>
			<th class=\"$col_3\">$h3</th>
			<th class=\"$col_4\">$h4</th>
			<th class=\"$col_5\">$h5</th>
			<th class=\"$col_6\">$h6</th>
			<th class=\"$col_7\">$h7</th>
			<th class=\"$col_8\">$h8</th> 
			<th class=\"$col_9\">$h9</th> 
		
		</tr>
		</thead>
		<tbody>";
  
	   $riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){

		   
		   
			  $riga++;    
			  $c1 = "";
			  $c2 = $row["codice"];

			  $c3 = $row["descrizione_articoli"];
			  $c4 = $row["prezzo"];
			  $c5 = $row["qOrd"];
			  $c6 = $row["Somma_qta"];             
			  $c7 = number_format($c4*$c6,2,",","");
			  
			  $c8 = q_scatole_intere_articolo_ordine($id,$row["id_articoli"]);
			  $ava = q_articoli_avanzo_articolo_ordine($id,$row["id_articoli"]);
			  $c9 = number_format($ava,2,",","");
			  
			  $scatole_totali = $scatole_totali + $c8;
			  $avanzo_totale = $avanzo_totale + $ava;
			  //$c8 = $row["Scatole_intere"];
			  //$c9 = $row["Avanzo"];
			  $c10 = "";
			  $c11 = "";
			  
			  $id_art = $row["id_articoli"];
			  $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
			  //echo "--id_ord: ". $row["id_ordini"];
			  //echo "--id_amico: ". $row["id_amico"];
			 // echo "--id user: ". $id_user."<br>";  
			  
			  //$subt_amico = valore_netto_singolo_amico($row["id_ordini"],$id_user,$row["id_amico"]); 
			  //echo "SUBT AMICO". $subt_amico."<br>";
			  //$subt_nome_amico = $c2;
			  
			  
			  $id_art= $row["id_articoli"];// ID articolo
			  
			  if(isset($is_pdf)){
				   $c1 = "";  
				}else{
				 $c1 = "<a class=\"option green awesome\" title=\"Cronologia\" href=\"oa_art_ord_crono.php?id=$id&id_art=$id_art\">Cr.</a>";
			  
				 
			  }
			  //$c1 .= "<a class=\"option red awesome\" title=\"Cancella\" href=\"../articoli/articoli_form_delete.php?id=$c10\">C</a>";  
	
			   if($c9>0){
			  $c9 = "<div class=\"campo_alert\">$c9</div>";
   
			   }else{
			   $c9 = "";    
			   }
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2>$c2</td>    
					<td $col_3>$c3 $misu</td>
					<td $col_4>$c4 $euro</td>
					<td $col_5>$c5</td>
					<td $col_6>$c6</td>
					<td $col_7>$c7 $euro</td>
					<td $col_8>$c8</td>
					<td $col_9>$c9</td>
				</tr>
			";
			
		   
		 }//end while
		 
		 
		 $h_table.= "</tbody>
					
					<tfoot>
					<tr class=\"total\">
						<td $col_1>&nbsp</td> 
						<td $col_2>&nbsp</td>    
						<td $col_3>&nbsp</td>
						<td $col_4>&nbsp</td>
						<td $col_5>&nbsp</td>
						<td $col_6>&nbsp</td>
						<td $col_7>&nbsp</td>
						<td $col_8>$scatole_totali</td>
						<td $col_9>$avanzo_totale</td>
					</tr>
			";
		 
	 
		 
		 $h_table.= "   </tfoot>
						</table>
						</div>
		 ";





?>