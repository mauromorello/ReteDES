<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$id_gas_user_code  = id_gas_user($id_user);
	  
	  
	  // MENU APERTO
	  $menu_aperto=3;
	  
	  
	   // Campi e intestazioni
	  include ("ordini_chiusi_sql.php");
	  
	  // MENU
	  $hide_single_operation ="ON";
	  include("ordini_chiusi_menu_core.php");

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"3%\" class=\"gas_c1\"";
	  $col_2="width=\"20%\" class=\"gas_c1\"";
	  $col_3="width=\"20%\" class=\"gas_c1\"";
	  $col_4="width=\"20%\" class=\"gas_c1\"";
	  $col_5="width=\"10%\" class=\"gas_c1\"";
	  $col_6="width=\"5%\" class=\"gas_c1\"";
	  $col_7="width=\"10%\" class=\"gas_c1\"";
	  $col_8="width=\"20%\" class=\"gas_c1\"";
	  $col_9="width=\"5%\" class=\"gas_c1\"";
	  $col_10="width=\"5%\" class=\"gas_c1\"";  
	  $col_11="style=\"vertical-align:middle\" ";    //opzioni
	  $col_12="width=\"5%\" class=\"gas_c1\"";
	  $col_13="width=\"5%\" class=\"gas_c1\"";

	  
	  // QUERY
	  
	  $query="SELECT retegas_ordini.id_ordini, 
			retegas_ordini.descrizione_ordini, 
			retegas_listini.descrizione_listini, 
			retegas_ditte.descrizione_ditte, 
			retegas_ordini.data_chiusura, 
			retegas_gas.descrizione_gas, 
			retegas_referenze.id_gas_referenze, 
			maaking_users.userid, 
			maaking_users.fullname,
			retegas_ordini.id_utente,
			retegas_ordini.is_printable,
			retegas_gas.id_gas
			FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas";
	  $where = " WHERE (((retegas_ordini.data_chiusura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$id_gas_user_code))
				";
	  $order_by = " ORDER BY retegas_ordini.data_chiusura DESC;";          
	  //echo $my_query;
	  
	  //echo $id_gas_user_code;
	  
	  if (empty($do)){$do="all";}
	  
	  switch ($do){ 
	  
		  case "all":
			  $titolo_tabella=" Tutti gli 'ordini chiusi'";
			  $my_query = $query.$where.$order_by;							 
		  break;
		  case "att":
			  $titolo_tabella=" Tutti gli ordini 'in attesa di conferma'";
			  $my_query = $query.$where." AND is_printable<>'1' ".$order_by;                             
		  break;
		  case "con":
			  $titolo_tabella=" Tutti gli ordini 'convalidati'";
			  $my_query = $query.$where." AND is_printable='1' ".$order_by;                             
		  break;
		  case "mga":
			  $titolo_tabella=" Tutti gli ordini proposti dal mio GAS";
			  $my_query = $query.$where." AND retegas_gas.id_gas='$gas' ".$order_by;                             
		  break;
		  case "aga":
			  $titolo_tabella=" Tutti gli ordini proposti da GAS esterni";
			  $my_query = $query.$where." AND retegas_gas.id_gas<>'$gas' ".$order_by;                             
		  break;
			  
		  
	  
	  }
	  
	  $mini_help = " <br> <div style=\"font-size:.8em;font-weight:normal;display:inline\">Clicca sulle intestazioni per ordinare le colonne, le operazioi di modifica si possono effettuare da ogni singola scheda ordine.</div>";
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = $db->sql_query($my_query);
	  $quanti_sono = $db->sql_numrows($result);	
	  // DIV AGGIUNTO PER PROVARE I MENU ALL'INTERNO DELLA TABELLA IN IE8
		  
	  $h_table .= "
	  
	  <div class=\"rg_widget rg_widget_helper\"> 
	  <div  style =\"margin-bottom:16px;\">$titolo_tabella ($quanti_sono) $mini_help</div>
	  <table id=\"ordini\" style=\"background-image:-webkit-gradient(linear,1250 225,0 255,from(#E0E0E0),to(#FFFFFF));\">		
	  <thead>
		<tr>
			<th>$h9</th>
			<th>$h11</th>
			<th>$h2</th>
			<th>$h3</th>
			<th>$h4</th>
			<th>$h5</th>
			<th>$h6</th>
			<th>$h12</th>
			<th>$h13</th>
					 
		</tr>
	   </thead>
	   <tbody> ";
  
	   $riga=0;  
		 while ($row = mysql_fetch_array($result)){
		 $riga++;
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = $row["$d3"];
			  $c4 = $row["$d4"];
			  $c5 = conv_datetime_from_db($row["$d5"]);
			  $c6 = $row["$d6"];
			  $c7 = $row["$d7"];
			  $c8 = $row["$d8"];
			  $c9 = $row["$d9"];
			  $c10 = $row["$d10"];
			  
			  $c12 = round(valore_totale_lordo_ordine($c1),2);
			  $c13 = round(valore_totale_mio_ordine_lordo($c1,$id_user),2);
			  
			  $totale_ordini = $totale_ordini + $c12;
			  $totale_mio = $totale_mio + $c13; 
	
			  if ($row["is_printable"]==1){
			  $style_avanzo =" style=\"background-image:-webkit-gradient(linear,0 20,40 40,from(#ACACAC),to(#FFFFFF)); text-align:left; padding-left:6px;\"";    
			  $c11="<div class=\"campo_ordine_convalidato\" title=\"Convalidato\" $style_avanzo>
					$c1
				 </div>";    
			  }else{
			  $style_avanzo =" style=\"background-image:-webkit-gradient(linear,0 20,40 40,from(#ff5c00),to(#FFFFFF)); text-align:left; padding-left:6px;\"";    
			  $c11="<div class=\"campo_ordine_chiuso\" title=\"In attesa di convalida\" $style_avanzo>
					$c1
				 </div>";    
			  }
	
		if ($c10==$id_user) {
			$c9="<div class=\"campo_mio\">
					$c9
				 </div>";
					
			//$c11 = "<div><a class=\"option yellow awesome\" href=\"ordini_chiusi_form_edit.php?id=$c1\" title=\"Modifica\">M</a></div>";
			//if(dettagli_ordine($c1)==0){
		//		$c11 .="<a class=\"option red awesome\" href=\"ordini_chiusi_form_delete.php?id=$c1\" title=\"Cancella\">C</a>";
		//	}   
		}else{
			//$c11="";
		}
		  
			
//		if(is_integer($riga/2)){  
//			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
//		}else{
//			$h_table.= "<tr class=\"$extra\">";    
//		}
		$h_table .= "<tr>";
		
		
		
		$h_table.= "<td $col_9>$c9</td>
					<td $col_11><a href=\"".$RG_addr["ordini_chiusi_form"]."?id=$c1\">$c11</a></td>
					<td $col_2><a href=\"".$RG_addr["ordini_chiusi_form"]."?id=$c1\">$c2</a></td>    
					<td $col_3>$c3</a></td>
					<td $col_4>$c4</td>
					<td $col_5>$c5</td>
					<td $col_6>$c6</td>
					<td $col_12>$c12</td>
					<td $col_13>$c13</td>  
				</tr>
			";
		 }//end while
		 
		 // riga totali
		 $h_table.= "
		 </tbody>
		 <tr class=\"totale_giallo\">
					 <td colspan=\"6\">&nbsp</td>
					 <td >Totali</td>                  
					 <td $col_12>$totale_ordini</td>
					 <td $col_13>$totale_mio</td>  
				 </tr>
			";
		 
		 
		 $h_table.= "
					
					</table>
					 </div>
		 ";
	  // END TABELLA ----------------------------------------------------------------------------
	  $posizione ="<b>ORDINI CHIUSI</b>";
	  $msg_info ="
				 <div style=\"text-align:right\">
					Tot. <b>$totale_ordini</b> Eu<br>
				   Mio. <b>$totale_mio</b> Eu<br>
				   Ordini: <b>$riga</b>
				  </div> ";
				  
	  include ("ordini_chiusi_main.php");
	  
	  
	  
}else{
	c1_go_away("?q=no_permission");
} 
?>