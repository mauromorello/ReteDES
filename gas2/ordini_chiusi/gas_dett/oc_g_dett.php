<?php
  if (eregi("oc_g_dett.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	  $numero_articoli_in_listino = articoli_n_in_listino($id);
	  $val_ord = valore_totale_ordine_qarr($id);
	  
	  
	 
	  if(isset($is_pdf)){
		  include("oc_g_dett_formatting_pdf.php");
	  }else{
		  include("oc_g_dett_formatting_screen.php");
	  }
	 
	  // se non è settato il gas allora prende il proprio 
	  //if(isset($id_g)){
		  //echo "IS SET ".$id_g;
	//	  $gas=$id_g;    
	 // }
	  
	  $gas_name=gas_nome($id_g);
	  $titolo_tabella="$gas_name (Dettaglio articoli ordinati)";
	  // TOOLTIPS

	  // INTESTAZIONI
	  
			
	  $h1="Utente";
	  $h2="Articolo";      
	  $h3="Descrizione";     
	  $h4="Q.ORD";
	  $h5="Q.ARR";
	  $h6="Prezzo<br>singolo";
	  $h7="Scat.";
	  $h8="Av.";
	  $h9="Totale Articolo";	   
	  $h10="Costi"; 
	  $h11="Totale";
	   


	  
	  
	  // QUERY LISTINI
	  $my_query=" SELECT
			retegas_dettaglio_ordini.qta_ord,
			retegas_dettaglio_ordini.qta_arr,
			maaking_users.userid,
			maaking_users.id_gas,
			maaking_users.fullname,
			retegas_articoli.codice,
			retegas_articoli.descrizione_articoli,
			retegas_articoli.prezzo,
			retegas_articoli.misura,
			retegas_articoli.u_misura,
			retegas_articoli.qta_scatola,
			(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS tot_riga,
			(retegas_dettaglio_ordini.qta_arr/retegas_articoli.qta_scatola) AS scatole_intere,
			retegas_dettaglio_ordini.id_dettaglio_ordini,
			retegas_articoli.id_articoli
			FROM
			retegas_dettaglio_ordini
			Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
			Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
			WHERE
			retegas_dettaglio_ordini.id_ordine =  '$id' AND
			maaking_users.id_gas =  '$id_g'
			";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
	  
  		  
	  $h_table .= "<br />
			<div class=\"ui-widget-header ui-corner-all padding_6px\"> 
			<div  style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th $col_1>$h1</th>
			<th $col_2>$h2</th>
			<th $col_3>$h3</th>
			<th $col_4>$h4</th>
			<th $col_5>$h5</th>
			<th $col_6>$h6</th>
			<th $col_7>$h7</th>
			<th $col_8>$h8</th>
			<th $col_9>$h9</th>
			<th $col_10>$h10</th>
			<th $col_11>$h11</th>
		</tr>";
  
	   $riga=0;  
	   
	   
	   
	   
	   while ($row = mysql_fetch_array($result)){
		   
		   if (($old_utente<>$row["userid"]) && $riga>0){
				
			include("oc_g_dett_subtotal.php");	
				
				
				
			}  
		   
		   
			  $riga++;
			  //if($gas<>$id_g){
				//$c1 = "";   
			  //}else{
				$c1 = $row["fullname"];  
			  //}    
			  
			  $c2 = $row["codice"]." (".$row["id_dettaglio_ordini"].")";
			  $c3 = $row["descrizione_articoli"];
			  $c4 = round($row["qta_ord"],2);
			  $c5 = round($row["qta_arr"],2);             
			  $c6 = $row["prezzo"];
			  $c7 = intval($row["qta_arr"]/$row["qta_scatola"]);
			  $c8 = $c5-intval($c7*$row["qta_scatola"]); 
			  $c9 = round($row["tot_riga"],2); 
			  $c10 = "";
			  $c11 = "";
			  $c12 = $row["c_user"];
			  
			  //Echo "Q_scatola = ".$row["qta_scatola"]."<br>";
			  
			  $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
			  //echo "--id_ord: ". $row["id_ordini"];
			  //echo "--id_amico: ". $row["id_amico"];
			 // echo "--id user: ". $id_user."<br>";  
			  
			  
			  $id_art= $row["id_articoli"];// ID articolo
			  
			  //if(isset($is_pdf)){
			//	   $c1 = "";  
			//	}else{
			//	 $c1 = "<a class=\"option orange awesome\" title=\"Cronologia\" href=\"#\">Cron.</a>";
			  
				 
			 // }
			  //$c1 .= "<a class=\"option red awesome\" title=\"Cancella\" href=\"../articoli/articoli_form_delete.php?id=$c10\">C</a>";  
	
			   if($c4<>$c5){
				if(($c5==0) or (empty($c5))){
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
		
		
		
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2>$c2</td>    
					<td $col_3>$c3 $misu</td>
					<td $col_4>$c4</td>
					<td $col_5>$c5  <br>$warning</td>
					<td $col_6>".number_format($c6,2,",","")." $euro</td>
					<td $col_7>$c7</td>
					<td $col_8>$c8</td>
					<td $col_9>".number_format($c9,2,",","")." $euro</td>
					<td $col_10>$nbsp</td>
					<td $col_11>$nbsp</td>
				</tr>
			";

		//Meccanismi
		$old_utente = $row["userid"];
		$subt_nome_user = $row["fullname"];
		$subt_user = $subt_user + $c9;
		$tot_articoli = $tot_articoli + $c9;	
			
			
			
			
		 }//end while
		// SUBTOTALE ULTIMO 
		 include("oc_g_dett_subtotal.php");    
		 
		
		// TOTALE 
		// $totale = valore_($id,$id_user);
		 include("oc_g_dett_total.php");    
		 
		 
		 $h_table.= "</table>
					 </div>
		 ";





?>
