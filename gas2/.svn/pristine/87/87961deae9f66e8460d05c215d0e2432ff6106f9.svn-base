<?php
  
include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$id_ditta = ditta_id_from_listino($id);
	  
	  //-------------------------------------------------DELETE
		if($do=="del"){
		
		
		//CONTROLLO IL PROPRIETARIO DEL LISTINO	
		if(listino_proprietario($id)<>$id_user){
			$msg="Questo listino non è stato inserito da te, oppure è già stato svuotato.<br> Impossibile svuotarlo";
			$id=$id_ditta;
			include("../ditte/ditte_form.php");    
			exit;
		}
		
		//CONTROLLO CHE SUL LISTINO NON SIANO STATI FATTI ORDINI
		if(quanti_ordini_per_questo_listino($id)<>0){
			$msg="Questo listino è già stato usato in un ordine.<br> Impossibile svuotarlo";
			$id=$id_ditta;
			include("../ditte/ditte_form.php");     
			exit;
		}
		
		//echo "articoli da eliminare con listino $id";
		$sql =  $db->sql_query("delete from retegas_articoli where retegas_articoli.id_listini=$id;");   
				
		$msg = "Svuotamento Riuscito";
		log_me("",$id_user,"LIS","SVU","Svuotato il listino $id dal suo proprietario",0,$sql);	
		$id=$id_ditta;
		include("../ditte/ditte_form.php"); 	
		exit;    
		}
	  
	  
	  
	  //-------------------------------------------------------
	  
	  
	  

	  
	  // MENU APERTO
	  $menu_aperto=1;
		
	  // QUERY
	  
	  $my_query="SELECT * FROM retegas_listini WHERE (id_listini='$id')  LIMIT 1";
	  
	  // SQL NOMI DEI CAMPI
	  $d1="id_listini";
	  $d2="descrizione_listini";
	  $d3="id_utenti";
	  $d4="id_tipologie";
	  $d5="id_ditte";
	  $d6="data_valido"; 
	  
	  // TITOLO TABELLA
	  $titolo_tabella="Listino cod. $id ";
	  
	  // INTESTAZIONI CAMPI
	  $h1="ID";
	  $h2="Nome";
	  $h3="Proponente";
	  $h4="Tipologia";
	  $h5="Valido fino al";
	  $h6="Ditta";
	  
	  
	  // TOOLTIPS

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="";
	  $col_2=""; 
  
	  
	  // OPZIONI
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = $db->sql_query($my_query);
	  $row = mysql_fetch_array($result);  
	  
	  //$h_table .= amici_menu_1();
	  
	  $h_table .= " 
					<div class=\"ui-state-error ui-corner-all padding_6px\" style=\"margin-bottom:20px\">
					<span class=\"ui-icon ui-icon-trash\" style=\"float:left; margin:0 7px 16px 0;\"></span>
					Stai cancellare tutti gli articoli di questo listino : sei sicuro ?
					<a href=\"listini_form_empty.php?id=$id&do=del\" class=\"awesome red medium\">SI</a> 
					<a href=\"../ditte/ditte_form.php?id=$id_ditta\" class=\"awesome green medium\">NO</a>
					</div>
					<div class=\"ui-widget-header ui-corner-tl ui-corner-tr action-icon padding-6px\">$titolo_tabella</div> 
					<table>
					";
		 
		 // VALORI DELLE CELLE da DB---------------------
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = fullname_from_id($row["$d3"]);
			  $c4 = tipologia_nome_from_listino($row["$d1"]);
			  $c5 = conv_date_from_db($row["$d6"]);
			  $c6 = ditta_nome_from_listino($c1);
		// VALORI CELLE CALCOLATE ----------------------      
$h_table .=  "
		<tr class=\"odd\">
			<th $col_1>$h1</th>
			<td $col_2>$c1</td>
		</tr>
		<tr  class=\"odd\">
			<th $col_1>$h2</th>
			<td $col_2>$c2</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h3</th>
			<td $col_2>$c3</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h4</th>
			<td $col_2>$c4</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h5</th>
			<td $col_2>$c5</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h6</th>
			<td $col_2>$c6</td>
		</tr>		
		</table>";

	  // END TABELLA ----------------------------------------------------------------------------
 $posizione=" <b>Svuotamento Listino</b>";	  
 include ("listini_main.php");
 
 
}else{
	c1_go_away("?q=no_permission");
}
?>