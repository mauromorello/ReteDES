<?php
  if (eregi("oc_m_art.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	  $numero_articoli_in_listino = articoli_n_in_listino($id);
	  
	  $titolo_tabella="La mia spesa (Riepilogo articoli ordinati)";
	  
	 
	  if(isset($is_pdf)){
		  include("oc_m_art_formatting_pdf.php");
	  }else{
		  include("oc_m_art_formatting_screen.php");
	  }
	  
	  
	  
	  // TOOLTIPS

	  // INTESTAZIONI
	  
			
	  $h1="Opzioni";
	  $h2="Articolo";      
	  $h3="Descrizione";
	  $h4="Quantità<br>ordinata";
	  $h5="Quantità<br>REALE";
	  $h6="Prezzo";
	  $h7="Totale Articolo";	   
	  $h8="Costi"; 
	  $h9="Totale";
	  


	  
	  
	  // QUERY LISTINI
	  $my_query="SELECT
					retegas_dettaglio_ordini.id_dettaglio_ordini,
					retegas_dettaglio_ordini.id_utenti,
					retegas_dettaglio_ordini.id_articoli,
					retegas_dettaglio_ordini.id_stati,
					retegas_dettaglio_ordini.data_inserimento,
					retegas_dettaglio_ordini.data_convalida,
					retegas_dettaglio_ordini.qta_ord,
					retegas_dettaglio_ordini.id_amico,
					retegas_dettaglio_ordini.id_ordine,
					retegas_dettaglio_ordini.qta_conf,
					retegas_dettaglio_ordini.qta_arr,
					retegas_dettaglio_ordini.timestamp_ord,
					retegas_articoli.id_articoli,
					retegas_articoli.id_listini,
					retegas_articoli.codice,
					retegas_articoli.u_misura,
					retegas_articoli.misura,
					retegas_articoli.descrizione_articoli,
					retegas_articoli.qta_scatola,
					retegas_articoli.prezzo,
					retegas_articoli.ingombro,
					retegas_articoli.qta_minima,
					retegas_articoli.qta_multiplo,
					retegas_articoli.articoli_note
					FROM
					retegas_dettaglio_ordini
					Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
					WHERE
					retegas_dettaglio_ordini.id_ordine =  '$id' AND
					retegas_dettaglio_ordini.id_utenti =  '$id_user'
					";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br /> 
			<div class=\"ui-widget-header ui-corner-all padding_6px\" style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th $col_1 >$h1</th>
			<th $col_2 >$h2</th>
			<th $col_3 >$h3</th>
			<th $col_4 >$h4</th>
			<th $col_5 >$h5</th>
			<th $col_6 >$h6</th>
			<th $col_7 >$h7</th>
			<th $col_8 >$h8</th>
			<th $col_9 >$h9</th> 
		</tr>";
  
	   $riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){
		   
		   //if (($old_amico<>$row["id_amico"]) && $riga>0){
				
			//	include("oc_mia_spesa_subtotal.php");	
				
				
				
			//}  
		   
		   
			  $riga++;    
			  $c1 = "";
			  $c2 = $row["codice"];
			  $c3 = $row["descrizione_articoli"];
			  $c4 = round($row["qta_ord"]);
			  $c5 = round($row["qta_arr"]);             
			  $c6 = $row["prezzo"]; 
			  $c7 = round($c5*$c6,2); 
			  $c8 = "";
			  $c9 = "";

              $subt_amico= $subt_amico + $c7;
			  
			  $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
			  //echo "--id_ord: ". $row["id_ordini"];
			  //echo "--id_amico: ". $row["id_amico"];
			 // echo "--id user: ". $id_user."<br>";  
			  
			  
			  $id_art= $row["id_articoli"];// ID articolo
			  
			  if(isset($is_pdf)){
				   $c1 = "";  
				}else{
				 $c1 = "<a class=\"option blue awesome\" title=\"Assegna\" href=\"../ordini_chiusi_ass_q.php?id=$id_art&id_ordine=$id&q_min=".$row["qta_minima"]."&id_dett=".$row["id_dettaglio_ordini"]."\">A</a>";
			  
				 
			  }
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
					<td $col_5>$c5 <br>$warning</td>
					<td $col_6>$c6 $euro</td>
					<td $col_7>$c7 $euro</td>
					<td $col_8>$nbsp</td>
					<td $col_9>$nbsp</td>
				</tr>
			";

		 }//end while
		 

		 
		 
		 $totale = round(valore_totale_mio_ordine($id,$id_user),2);
		 $subt_nome_amico = "Me stesso";
         
         include("oc_m_art_subtotal.php");
         include("oc_m_art_total.php");    
		 
		 
		 $h_table.= "</table>
					 
		 ";





?>
