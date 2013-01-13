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
	  
	  // TITOLO TABELLA
	  $titolo_tabella="Ordini aperti - <span class=\"small_link\">Le operazioni sugli ordini si possono effettuare dalle loro singole schede</span>";
	  
	   // Campi e intestazioni
	  include ("ordini_aperti_sql.php");
	  
	  // MENU
	  $hide_single_operation="ON";
	  include ("ordini_aperti_menu_core.php");

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"5%\" class=\"gas_c1\"";
	  $col_2="width=\"*\" class=\"gas_c1\"";
	  $col_3="width=\"20%\" class=\"gas_c1\" style=\"text-align:left\"";
	  $col_4="width=\"10%\" class=\"gas_c1\"";
	  $col_5="width=\"15%\" class=\"gas_c1\"";
	  $col_6="width=\"5%\" class=\"gas_c1\"";
	  $col_7="width=\"10%\" class=\"gas_c1\"";
	  $col_8="width=\"20%\" class=\"gas_c1\"";
	  $col_9="width=\"5%\" class=\"gas_c1\"";
	  $col_10="width=\"5%\" class=\"gas_c1\"";  
	  $col_11="style=\"vertical-align:middle\" ";    //opzioni
	  $col_12="width=\"5%\" class=\"gas_c1\"";
	  $col_13="width=\"5%\" class=\"gas_c1\"";

	  
	  // QUERY
	  
	  $my_query="SELECT retegas_ordini.id_ordini, 
			retegas_ordini.descrizione_ordini, 
			retegas_listini.descrizione_listini, 
			retegas_ditte.descrizione_ditte, 
			retegas_ordini.data_chiusura, 
			retegas_gas.descrizione_gas, 
			retegas_referenze.id_gas_referenze, 
			maaking_users.userid, 
			maaking_users.fullname,
			retegas_ordini.id_utente,
			retegas_ordini.id_listini,
			retegas_ditte.id_ditte,
			retegas_ordini.data_apertura
			FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
			WHERE (((retegas_ordini.data_chiusura)>NOW())
			AND ((retegas_ordini.data_apertura)<NOW()) 
			AND ((retegas_referenze.id_gas_referenze)=$id_gas_user_code))
			ORDER BY retegas_ordini.data_chiusura ASC ;";
	  
	  //echo $my_query;
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = $db->sql_query($my_query);
		
	  //$h_table .= ditte_menu_1();
		  
	  $h_table .= "<div class=\"rg_widget rg_widget_helper\"> 
	  <div  style =\"margin-bottom:16px;\">$titolo_tabella</div>
	  <table id=\"ordini_aperti\" style=\"background-image:-webkit-gradient(linear,1250 225,0 255,from(#E0E0E0),to(#FFFFFF));\">		
	  <thead>
		<tr>
			<th>$h9</th>		
			<th>$h2</th>
			<th>Info</th>
			<th>Chiude il</th>
			<th>$h6</th>
			<th>&nbsp;</th>
			<th>$h12</th>
			<th>$h13</th>
			<th>Part.</th>		 
		</tr>
		</thead>
		<tbody>
		";
  
		//scopro la data minima e massima e la cifra massima
		
		$min_data=date("d/m/Y");
		$max_valore = 0;
		$max_giorni =0;
		//echo gas_mktime($min_data)." - MIN DATA <br>";
		while ($row_stat = mysql_fetch_array($result)){ 
			//echo $row_stat[1]."<br>";
			$data_db = conv_date_from_db($row_stat["data_apertura"]);
			//echo $data_db."<br>";
			//echo gas_mktime($data_db)." - data mk <br>";
			//echo gas_mktime(date("d/m/Y"))." - OGGI <br>";
			//echo gas_mktime(date("d/m/Y"))-gas_mktime($data_db)." - DIFF <br>";
			//echo (gas_mktime(date("d/m/Y"))-gas_mktime($data_db))/(60*60*24)." - DIVISO 60*60*24 <br>";    
			
			$diff =  intval((gas_mktime(date("d/m/Y"))-gas_mktime($data_db))/(60*60*24));
			
			if($diff>$max_giorni){$max_giorni=$diff;}
			if(gas_mktime($data_db)<gas_mktime($min_data)){$min_data=$data_db;}
			$valore_questo_ordine = valore_totale_ordine($row_stat[0]);
			if($valore_questo_ordine>$max_valore){$max_valore=$valore_questo_ordine;}
		}
		$max_giorni++;
		
		
		//echo "Min data : ".$min_data."<br>";
		//echo "Giorni : ".$max_giorni."<br>";
		//echo "Max valore : ".$max_valore."<br>"; 
		//--------------------------------------------
  
		mysql_data_seek($result, 0);
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
			  $c_listini = $row["id_listini"];
			  $c_ditte = $row["id_ditte"];
			  $c_tipologia = tipologia_nome_from_listino($c_listini);
			  $c12 = round(valore_totale_ordine($c1),2);
			  $c13 = round(valore_totale_mio_ordine($c1,$id_user),2);
			  

			  
			  
			   
		if($c9=="Retegas.AP"){
			//echo "C9 = $c9<br>";
			$c9 = "
					<a class=\"small awesome beige\" href=\"ordini_aperti_diventa_referente_form.php?id=$c1\">
					Diventa referente
					</a>
					";
		}
		
		if ($c10==$id_user) {
			$c9="<div class=\"campo_mio\">            
					$c9                    
				 </div>";
			//$c15="<div class=\"campo_mio\">$c1</div>";		
			 $c15=$c1; 
		}else{
			$c9="<a href=\"/gas2/utenti/utenti_form.php?id=$c8\">            
					$c9                    
				 </a>";
			 $c15=$c1; 
			//$c15=$c1;
		}
		  
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		}else{
			$h_table.= "<tr class=\"$extra\">";    
		}
		
		
		
		$valori_sparkline = crea_grafico_sparkline($c1,$min_data,$max_giorni,$max_valore);
		$range_max = $max_valore;
		//echo "VAL ".$valori_sparkline."<br>";            
		$sparkline_data="<span class='inlinesparkline'>".$valori_sparkline."</span>";			
		
		$info = "<b>Ditta:</b></br>
					<a href='../ditte/ditte_form.php?id=$c_ditte'>$c4</a><br>
				<b>Listino:</b><br>    
					<a href='../listini/listini_form.php?id=$c_listini'>$c3</a><br>
				<b>Tipologia:</b><br>
					$c_tipologia<br>";
		
		$prog_value = (int)avanzamento_ordine_from_id_ordine($c1);			
		
		$data_prog = "{ Current: $prog_value, aBackground: '#F51010' }";
		
		if(($prog_value >= 0) AND ($prog_value < 20)){
			$data_prog = "{ Current: $prog_value, aBackground: '#29F213' }";    
		}
		if(($prog_value >= 20) AND ($prog_value < 40)){
			$data_prog = "{ Current: $prog_value, aBackground: '#D0F114' }";    
		}
		if(($prog_value >= 40) AND ($prog_value < 60)){
			$data_prog = "{ Current: $prog_value, aBackground: '#F3D112' }";    
		}
		if(($prog_value >= 60) AND ($prog_value < 80)){
			$data_prog = "{ Current: $prog_value, aBackground: '#F54910' }";    
		}
		
		$bacino_totale = ordine_bacino_utenti($c1);
		$bacino_partecipanti = ordine_bacino_utenti_part($c1);
		
		$sparkline_data_pie ="<span class='sparkline_pie'>".$bacino_totale.",".$bacino_partecipanti."</span>";
		
		
		
		$h_table.= "<td width=\"10%\">$c9</td>
					
					<td style=\"text-align:left;\"><a href=\"/gas2/ordini_aperti/ordini_aperti_form.php?id=$c1\">$c2 ($c15)</a></td>    
					<td><a class=\"awesome celeste small\" title=\"$info\">Info</a></td>				
					<td>$c5<div class=\"progressbar $data_prog\"></div></td>
					<td width=\"15%\">$c6</td>
					<td style=\"vertical-align:bottom;\">$sparkline_data</td>
					<td width=\"5%\">$c12</td>
					<td width=\"5%\">$c13</td>
					<td style=\"vertical-align:middle\">$sparkline_data_pie</td>  
				</tr>
			";
		 }//end while

		 $h_table.="</tbody>
					</table>
					</div>
		 ";
	  // END TABELLA ----------------------------------------------------------------------------
	  
	  
	  $posizione ="<b>ORDINI APERTI</b>";
	  $table_sorter_name = "ordini_aperti";
	  $qtip_on="SI";
	  $sparkline_on="SI";
	  $sparkline_on_pie="SI";
	  $sparkline_pie_reference = ".sparkline_pie"; 
	  $progression = "SI";
	  
	  include ("ordini_aperti_main.php");
	  
	  
	  
}else{
	c1_go_away("?q=no_permission");
} 
?>
