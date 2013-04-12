<?php
  if (eregi("ag_table_core.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}
// --------------START LISTINI
	  // TITOLO TABELLA
	  $nome_listino=listino_nome($id);
	  $numero_articoli_in_listino = articoli_n_in_listino($id);
	  
	  $titolo_tabella="Riepilogo GAS partecipanti all'ordine n .$id";
	  $totale_ordine = valore_totale_ordine_qarr($id);
	 
	  if(isset($is_pdf)){
		  include("ag_table_core_formatting_pdf.php");
	  }else{
		  include("ag_table_core_formatting_screen.php");
	  }
	  
	  
	  
	  // TOOLTIPS

	  // INTESTAZIONI
	  
	  $h1="";      
	  $h2="GAS";
	  $h3="Referente";      
	  $h4="Arrivati";
	  $h5="Totale netto";
	  $h6="Costi";
	  $h7="TOTALE";

	  
	  


	  
	  
	  // QUERY LISTINI
	  $my_query="SELECT retegas_gas.descrizione_gas, 
		maaking_users_1.fullname, 
		retegas_referenze.id_utente_referenze,
		Sum(retegas_dettaglio_ordini.qta_ord) AS SommaDiqta_ord, 
		Sum(retegas_dettaglio_ordini.qta_arr) AS SommaDiqta_arr, 
		Sum(prezzo*qta_arr) AS tot_art_arr,
		retegas_gas.id_gas 
		FROM (((((retegas_dettaglio_ordini INNER JOIN retegas_ordini ON retegas_dettaglio_ordini.id_ordine = retegas_ordini.id_ordini) INNER JOIN maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid) INNER JOIN retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas) INNER JOIN retegas_referenze ON (maaking_users.id_gas = retegas_referenze.id_gas_referenze 
		AND ((retegas_referenze.id_ordine_referenze)=$id)) 
		AND (retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze)) INNER JOIN maaking_users AS maaking_users_1 ON retegas_referenze.id_utente_referenze = maaking_users_1.userid) INNER JOIN retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
		GROUP BY retegas_gas.descrizione_gas, 
		maaking_users_1.fullname, 
		retegas_referenze.id_utente_referenze, 
		retegas_dettaglio_ordini.id_ordine;"; 
	  
	  
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br />
			<div class=\"ui-widget-header ui-corner-all padding_6px\"> 
			<div  style = \"margin-bottom:6px;\">$titolo_tabella</div>

			<table class=\"mia_spesa\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" rules=\"rows\">
			
		<tr>
			<th>$h1</th>
			<th>$h2</th>
			<th>$h3</th>
			<th>Ordinati</th>
			<th>$h4</th>
			<th>$h5</th>
			<th>$h6</th>
			<th>$h7</th>
		</tr>";
  
	   $riga=0;  
	   $somma_c4=0;
       $somma_c9=0;
	   
	   while ($row = mysql_fetch_array($result)){
		   
		      if (($id_g<>$row["id_gas"]) && $riga>0){
                
              include("ag_table_subtotal.php");       

              } 
		   
			  $riga++;    
			  $c1 = "";	  
			  $c2  =$row["descrizione_gas"]; 
			  $c3 = $row["fullname"];
              
              $c9 = round($row["SommaDiqta_ord"],2);
              
              
			  $c4 = round($row["SommaDiqta_arr"],2);
              
              
              
              $scat_ord = "";
              
			  $c5 = round($row["tot_art_arr"],2);
			  
			  
			  $gas_percent = ($c5/$totale_ordine)*100;
			  $trasporto = valore_trasporto($ordine,$gas_percent);
			  $gestione =  valore_gestione($ordine,$gas_percent);
              
              

			  $c6 = $trasporto+$gestione; 
			  $c7 =  $trasporto+$gestione+$c5;
			  $subt_gas = $c5; 
			  $id_g=$row["id_gas"];

			  
			  if(isset($is_pdf)){
				   $c1 = "";  
				}else{
				 $c1 = "<a class=\"option green awesome\" title=\"Riepilogo\" href=\"../gas_art/oc_g_art_report.php?id=$id&id_g=$id_g\">R</a>
						<a class=\"option yellow awesome\" title=\"Dettaglio\" href=\"../gas_dett/oc_g_dett_report.php?id=$id&id_g=$id_g\">D</a>";
			  
				 
			  }

			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2>$c2</td>    
					<td $col_3>$c3</td>
					<td $col_4>$c9</td>
					<td $col_5>$c4</td>
					<td $col_6>$c5 $euro</td>
					<td $col_7>". number_format(($c6),2,",","") ." $euro</td>
					<td $col_8>". number_format(($c7),2,",","") ." $euro</td>
				</tr>
			";
				   $somma_articoli=$somma_articoli + $c4;
				   $somma_articoli_ordinati=$somma_articoli_ordinati + $c9;
				   $somma_netto=$somma_netto+$c5;
				   $somma_costi=$somma_costi+$c6;
				   $somma_lorda=$somma_lorda+$c7;
  
  
		 }//end while
		 

		
		 include("ag_table_subtotal.php");
		 //include("ag_table_total.php");
					 
 
		 
		 
		 $h_table.= "</table>
					 </div>
		 ";		





?>
