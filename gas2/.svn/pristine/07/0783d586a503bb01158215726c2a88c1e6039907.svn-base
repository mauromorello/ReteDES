<?php
  if (eregi("oc_lis_art.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	  $numero_articoli_in_listino = articoli_n_in_listino($id);
	  
	  $titolo_tabella="Listone Articoli Ordinati  Arrivati";
	  
	 
	  if(isset($is_pdf)){
		  include("oc_lis_art_formatting_pdf.php");
	  }else{
		  include("oc_lis_art_formatting_screen.php");
	  }
	  
	  
	  
	  // TOOLTIPS


	  


	  
	  
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
					retegas_dettaglio_ordini.id_ordine =  '$id'
					ORDER BY retegas_articoli.codice ASC, retegas_dettaglio_ordini.qta_ord DESC
					LIMIT 1000;";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	$h_table .= '<table>';
	$riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){
		   
  
		   
		   
			  $riga++;
			  $c0 =$row["id_articoli"];    
			  $c1 = fullname_from_id($row["id_utenti"]);
			  $c2 = $row["codice"];
			  $c3 = $row["descrizione_articoli"];
			  $c4 = round($row["qta_ord"],2);
			  $c5 = round($row["qta_arr"],2);             

			  $c8 = gas_user($row["id_utenti"]);
			  $c9 = $row["qta_scatola"]; 
			  $c10 =q_articoli_avanzo_articolo_singolo($c9,$c5);
			  $c11 =q_scatole_intere_articolo_singolo($c9,$c5);
			  
			  if($c10==0){$c10="$nbsp";}
			  if($c11==0){$c11="$nbsp";}
			  
			  $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
			  //echo "--id_ord: ". $row["id_ordini"];
			  //echo "--id_amico: ". $row["id_amico"];
			 // echo "--id user: ". $id_user."<br>";  
			  
			  
			  $id_art= $row["id_articoli"];// ID articolo
			  
			   if($c4<>$c5){
				if(($c5==0) or (empty($c5))){
				  $warning = "<div class=\"campo_alert\">ANNULLATA</div>";
				  }else{
				  $warning = "<div class=\"campo_alert\">MODIFICATA</div>";  
				}   
			   }else{
					$warning = "";    
			   }
		   

			
			if($old_amico==$row["codice"]){$c2_echo="$nbsp";$c3_echo="$nbsp";$misu_echo="$nbsp";}else
									  {$c2_echo=$c2;$c3_echo=$c3;$misu_echo=$misu;}
				   $vecchi_codici = $c2 ." - ".$c3; 
		   
		   if (($old_amico<>$row["codice"]) | $riga==0){
				include("oc_lis_art_subtotal.php");    
		   }
		
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd\">";    // Colore Riga
		}else{
			$h_table.= "<tr>";    
		}
	
	   
		
		$h_table.= "		
							<td $col_1>$c2_echo</td> 
							<td $col_2>$c3_echo $misu_echo</td> 
							<td $col_3>$c8</td>     
							<td $col_4>$c1</td>                
							<td $col_5>$c4</td> 				
							<td $col_6>$c5<br />$warning</td> 			
							<td $col_7>$c11</td> 
							<td $col_8>$c10</td> 
							<td $col_9>$nbsp</td> 
							</tr>
		";

		   
		 $old_amico = $row["codice"];   
		 }//end while
		 
		 $h_table.='</table>';

		 //include("oc_lis_art_subtotal.php");
		 
		// $totale = valore_totale_mio_ordine($id,$id_user);
		 include("oc_lis_art_total.php");    
		 
		 
		//$h_table.= "</div>";





?>
