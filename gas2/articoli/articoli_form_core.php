<?php

if (eregi("articoli_form_core.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
} 

		// QUERY
	  
	  $my_query="SELECT * FROM retegas_articoli WHERE  (id_articoli='$id') LIMIT 1";
	  
	  // Campi e intestazioni
	  include ("articoli_sql.php");
			  
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"30%\"";
	  $col_2=""; 

	 // OPZIONI SCHEDA
	 if(articoli_in_ordine($id)==0){
		if(articoli_user($id)==$id_user){
			$opt1 .="<a class=\"small yellow awesome destra\" href=\"articoli_form_edit.php?id=$id\">Modifica</a>";     
			$opt2 .="<a class=\"small red awesome destra\" href=\"articoli_form_delete.php?id=$id\">Elimina</a>";
		}
	 }
	  
	  
	  
	  // OPZIONI
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = $db->sql_query($my_query);
	  $row = mysql_fetch_array($result);  
	  
	  //$h_table .= articoli_menu_1($id,$id_user);
	  

		 
		 // VALORI DELLE CELLE da DB---------------------
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $lis = listino_nome($c2);
			  $dit = ditta_nome_from_listino($c2);
			  $id_dit = ditta_id_from_listino($c2);
			  
			  
			  $c3 = $row["$d3"];
			  $c4 = $row["$d4"];
			  $c5 = $row["$d5"];
			  $c6 = $row["$d6"];
			  $c7 = $row["$d7"];
			  $c8 = $row["$d8"];
			  $c9 = $row["$d9"];
			  $c10 = $row["$d10"];
			  $c11 = $row["$d11"];
			  $c12 = $row["$d12"];
			  $c13 = $row["articoli_unico"];
			  
			  // VALORI CELLE CALCOLATE ----------------------      
			  if($c13==1){$univ="<b>ARTICOLO UNIVOCO</b>";}else{$univ="ARTICOLO CUMULABILE";};
			  
			  
		 // TITOLO TABELLA
			$titolo_tabella="Articolo $id, dal listino <a href=\"../listini/listini_form.php?id=$c2\">$lis</a>, 
			ditta <a href=\"../ditte/ditte_form.php?id=$id_dit\">$dit</a>";
		 
		 
		 
$h_table .= "<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">
			<div style=\"margin-bottom:10px;\">$titolo_tabella  $opt1 $opt2</div>
			 
			 <table>
			 <tr>
			 <td>";         
$h_table .=  "<table>
		<tr class=\"odd\">
			<th $col_1>$h1</th>
			<td $col_2>$c1</td>
		</tr>
		<tr  class=\"odd\">
			<th $col_1>$h2</th>
			<td $col_2>$c2 ($lis)</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h3</th>
			<td $col_2>$c3</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h4</th>
			<td $col_2>$c4</td>
		</tr>
		<tr class=\"odd\" >
			<th $col_1>$h5</th>
			<td $col_2>$c5</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h6</th>
			<td $col_2>$c6</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>Tipo Gestione</th>
			<td $col_2>$univ</td>
		</tr>
		</table>
		</td>
		
		<td>
		
		<table>        
		
		<tr class=\"odd\">
			<th $col_1>$h7</th>
			<td $col_2>$c7</td>
		</tr>
		<tr  class=\"odd\">
			<th $col_1>$h8</th>
			<td $col_2>$c8</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h9</th>
			<td $col_2>$c9</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h10</th>
			<td $col_2>$c10</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h12</th>
			<td $col_2>$c12</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</div>";

	  // END TABELLA DITTA -----------------------------------------------------------------------


?>