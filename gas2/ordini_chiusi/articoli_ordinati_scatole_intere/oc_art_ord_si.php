<?php
  if (eregi("oc_art_ord_si.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}

	  $borders_excel="0";
	  $titolo_tabella="Raggruppamento articoli ORDINATI (solo scatole intere)";
	  
	 
	  if(isset($is_pdf)){
		  include("oc_art_ord_si_formatting_pdf.php");
	  }else{
		  include("oc_art_ord_si_formatting_screen.php");
	  }
	  
	  if(isset($is_excel)){
		   $titolo_tabella="";
		   $euro="";
		   $borders_excel="1";
		   $style_numbers_excel=' style="mso-number-format:"\#\,\#\#0\.000";" ';     
	  }
	  
	  // TOOLTIPS

	  
	  


	  
	  
	  // QUERY LISTINI
	  $my_query="SELECT
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
		
		  
	  $h_table .= "<br>
			<div class=\"ui-widget-header ui-corner-all padding_6px\"> 
			<div  style = \"margin-bottom:16px;\">$titolo_tabella</div>

			<table class=\"mia_spesa\" border=\"$borders_excel\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th $col_1\>Codice</th>
			<th $col_2\>Descrizione</th>
			<th $col_3\>Prezzo unitario</th>
			<th $col_4\>Scatole intere</th>
			<th $col_5\>Quantità per scatola</th>
			<th $col_6\>Totale riga</th>
		</tr>";
  
	   $riga=0;  
	   $totale=0;
	   $total_Add=0;
	   
	   while ($row = mysql_fetch_array($result)){

		   
		   
			  $riga++;    
			  $c1 = "";
			  $c2 = $row["codice"];
			  $c3 = $row["descrizione_articoli"];
			  $c4 = (float)$row["prezzo"];
			  $c5 = $row["qOrd"];
			  $c6 = (int)$row["Scatole_intere"];             
			  
			  $c8 = $row["qta_scatola"];
			  $temp = round(($c4 * ($c6 * $c8)),4);
			  $c7 = number_format($temp,2,".","");
			  
			  $id_art = $row["id_articoli"];
			  $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
		  
			  $id_art= $row["id_articoli"];// ID articolo
			  
		if($c6>0){//Salto righe senza scatole intere	
		
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		
		
		$c4 = number_format($c4,2,".","");
		
		$h_table.= "<td $col_1>$c2</td>    
					<td $col_2 $style_numbers_excel>$c3 $misu</td>
					<td $col_3 $style_numbers_excel>$c4 $euro</td>
					<td $col_4>$c6</td>
					<td $col_5>$c8</td>
					<td $col_6 $style_numbers_excel>$c7 $euro</td>
				</tr>
			";
			
			$totale = $totale + $temp;
			//echo "C7 = $c7, Totale = $totale $c4 * ($c6 / $c8) = ".($c4 * ($c6 / $c8))."<br>";
			$n_scatole_intere = $n_scatole_intere + $c6;
		}else{
			//torno indietro di una riga per non perdere la formattazione	
			$riga--;
		}	
		   
		}//end while
		
		include("oc_art_ord_si_total.php"); 
		 
	 
		 
		 $h_table.= "</table>
					 </div> 
		 ";





?>
