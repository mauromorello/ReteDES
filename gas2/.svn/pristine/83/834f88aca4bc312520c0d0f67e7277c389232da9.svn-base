<?php
  if (eregi("oc_g_art.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	  $numero_articoli_in_listino = articoli_n_in_listino($id);
	  
	  
	  
	 
	  if(isset($is_pdf)){
		  include("oc_g_art_formatting_pdf.php");
	  }else{
		  include("oc_g_art_formatting_screen.php");
	  }
	 
	  // se non è settato il gas allora prende il proprio 
	  if(isset($id_g)){
		  //echo "IS SET ".$id_g;
		  $gas=$id_g;    
	  }
	  
	  $gas_name=gas_nome($gas);
	  $titolo_tabella="$gas_name (Riepilogo articoli)";
	  // TOOLTIPS

	  // INTESTAZIONI
	  
			
	  
	  $h1="Articolo";      
	  $h2="Descrizione";     
	  $h3="Quantità<br>ordinata";
	  $h4="Quantità<br>REALE";
	  $h5="Prezzo";
	  $h6="Totale Articolo";	   
	  $h7="Costi"; 
	  $h8="Totale";
	  $h9="Utenti"; 


	  
	  
	  // QUERY LISTINI
	  $my_query="SELECT
					count(retegas_dettaglio_ordini.id_utenti) AS c_user,
					Sum(retegas_dettaglio_ordini.qta_ord) AS t_q_ord,
					Sum(retegas_dettaglio_ordini.qta_arr) AS t_q_arr,
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
					Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
					WHERE
					retegas_dettaglio_ordini.id_ordine =  '$id' AND
					maaking_users.id_gas =  '$gas'
					GROUP BY
					retegas_articoli.id_articoli
					ORDER BY
					retegas_articoli.codice ASC
					";
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br />
			<div  class=\"ui-widget-header ui-corner-all padding_6px\"> 
			<div style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th $col_1>$h1</th>
			<th $col_2>$h2</th>
			<th $col_9>$h9</th>
			<th $col_3>$h3</th>
			<th $col_4>$h4</th>
			<th $col_5>$h5</th>
			<th $col_6>$h6</th>
			<th $col_7>$h7</th>
			<th $col_8>$h8</th>
			
		</tr>";
  
	   $riga=0;  
	   
	   
	   while ($row = mysql_fetch_array($result)){
		   
		   //if (($old_amico<>$row["id_amico"]) && $riga>0){
				
			//	include("oc_mia_spesa_subtotal.php");	
				
				
				
			//}  
		   
		   
			  $riga++;    
			  
			  $c1 = $row["codice"];
			  $c2 = $row["descrizione_articoli"];
			  $c3 = round($row["t_q_ord"],2);
			  $c4 = round($row["t_q_arr"],2);             
			  $c5 = $row["prezzo"]; 
			  $c6 = round($c4*$c5,2); 
			  $c7 = "";
			  $c8 = "";
			  $c9 = $row["c_user"];
			  
			  $tot_articoli = $tot_articoli + $c6;
              $subt_gas = $subt_gas + $c6;
			  
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
	
			   if($c3<>$c4){
				if(($c4==0) or (empty($c4))){
                  $c4="";  
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
					<td $col_2>$c2  $misu</td>    
					<td $col_9>$c9</td>
					<td $col_3>$c3</td>
					<td $col_4>$c4 $warning</td>
					<td $col_5>".number_format($c5,2,",","")." $euro</td>
					<td $col_6>".number_format($c6,2,",","")." $euro</td>
					<td $col_7>$nbsp</td>
					<td $col_8>$nbsp</td>
				</tr>
			";

		 }//end while
		 

		 
		 
		// $totale = valore_($id,$id_user);
		
		
		//  da sistemare
		 include("oc_g_art_subtotal.php");
		 include("oc_g_art_total.php");    
		 
		 
		 $h_table.= "</table>
					 </div>
		 ";





?>
