<?php
if (eregi("articoli_table_core.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: index.php"); die();
}  
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	 
	  
	  $titolo_tabella="ARTICOLI del listino ''$nome_listino''";
	  
	  // INTESTAZIONI
	  
	  $h1="Codice";      
	  $h2="Descrizione";
	  $h3="Misura";      
	  $h4="Prezzo";
	  $h5="Scatola/Multiplo";
	  $h6="Note";
	  $h7="Opzioni";
	  
	  // TOOLTIPS

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"5%\" class=\"gas_c1\"";
	  $col_2="width=\"25%\" class=\"gas_c1\"";
	  $col_3="width=\"15%\" class=\"gas_c1\"";
	  $col_4="width=\"15%\" class=\"gas_c1\"";
	  $col_5="width=\"10%\" class=\"gas_c1\"";
	  $col_6="width=\"10%\" class=\"gas_c1\"";  
	  $col_7="width=\"15%\" style=\"vertical-align:middle\" ";    //opzioni

	  
	  // QUERY LISTINI
	  $my_query="SELECT 
				 *    
				 FROM retegas_articoli
				 WHERE id_listini='$id'
				 ORDER BY retegas_articoli.codice ASC;";
	  
	  // NOMI DEI CAMPI
	  $d1="codice";
	  $d2="descrizione_articoli";
	  $d3="u_misura";
	  $d4="misura";
	  $d5="prezzo";
	  $d6="qta_scatola";
	  $d7="qta_minima";
	  $d8="id_listini";
	  $d9="articoli_note";
	  $d10 = "id_articoli";
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = mysql_query($my_query);
		
		  
	  $h_table .= "<br /> 
			<div class=\"ui-widget-header ui-corner-all padding_6px\" style = \"margin-bottom:6px;\">$titolo_tabella<br>

			<table>
			
		<tr>
			<th>$h1</th>
			<th>$h2</th>
			<th>$h3</th>
			<th>$h4</th>
			<th>$h5</th>
			<th>$h6</th>
			<th>UNIVOCO</th>
			<th>RAGGR.</th>         
		</tr>";
  
	   $riga=0;  
		 while ($row = mysql_fetch_array($result)){
		 $riga++;
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = $row["$d3"]." ".  $row["$d4"];
			  $c4 = $row["$d5"];
			  $c5 = $row["$d6"]." / ".$row["$d7"];              
			  if(!empty($row["$d9"])){$c6=trim(substr(strip_tags($row["$d9"]),0,15)) ." ...";}else{$c6="";}
			  $c6_alt = htmlentities($row["$d9"]); 
			  $c8 = $row["$d8"];
			  $c10=  $row["$d10"];       // ID articolo
			  $c7 = $row["articoli_opz_1"]." - ".$row["articoli_opz_2"]." - ".$row["articoli_opz_3"];
			  
			  

			  
			  
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		if($row["articoli_unico"]==1){$au="SI";}else{$au="";};
		
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2><a href=\"../articoli/articoli_form.php?id=$c10\">$c2<a></td>    
					<td $col_3>$c3</td>
					<td $col_4>$c4</td>
					<td $col_5>$c5</td>
					<td $col_6><a title=\"$c6_alt\">$c6</a></td>
					<td $col_5>$au</td>
					<td $col_7>$c7</td>  
				</tr>
			";
		 }//end while

		 $h_table.= "</table>
					 </div>
		 ";
?> 