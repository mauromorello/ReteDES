<?php
  
include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		//$gas_name = gas_nome($gas);
		//$id_ditta = ditta_id_from_listino($id);
		
		
		
		//   CONTROLLI :
		// ORDINE DI USER
		// AL MOMENTO NESSUN PARTECIPANTE
		//Echo "IO : ".$id_user ."<br>";
		//Echo "Ordine : ".$id ."<br>";  
		//echo "IO COSA SONO :" . ordine_io_cosa_sono($id,id_user);
		
		
		if (id_referente_ordine_globale($id)<>$id_user){
			$msg="Sembra che tu non sia il referente di questo ordine ...";
			include("ordini_chiusi_table.php");    
			exit;    
		}
		
		if (dettagli_ordine($id)>0){
			$msg="Sembra che tu qualcuno abbia gia' ordinato articoli in questo ordine ....<br>
			Per poter cancellare un ordine questo dev'essere vuoto.";
			include("ordini_chiusi_table.php");    
			exit;   
		}
		
  
	  //-------------------------------------------------DELETE
		if($do=="del"){
		$nomordine = descrizione_ordine_from_id_ordine($id);  	
		
		//tabella ordini
		$sql = $db->sql_query("delete from  retegas_ordini where retegas_ordini.id_ordini=$id LIMIT 1;");
		
		//tabella referenze
		$sql = $db->sql_query("delete from  retegas_referenze where retegas_referenze.id_ordine_referenze='$id';");
		
			
		$msg = "Eliminazione riuscita";	
		log_me($id,$id_user,"ORD","MOD","Eliminazione ordine $id, ($nomordine)",0,$sql);
	
		include("ordini_chiusi_table.php"); 	
		exit;    
		}
	  
	  
	  
	  //-------------------------------------------------------
	  
	  
	  

	  
	  // MENU APERTO
	  $menu_aperto=1;
		
	  // QUERY
	  
	  $my_query="SELECT * FROM retegas_ordini WHERE (id_ordini='$id')  LIMIT 1";
	  
	  // SQL NOMI DEI CAMPI

	  
	  // TITOLO TABELLA
	  $titolo_tabella="";
	  
		  
	  // TOOLTIPS

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="";
	  $col_2=""; 
  
	  
	  // OPZIONI
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = mysql_query($my_query);
	  $row = mysql_fetch_array($result);  
	  
	  //$h_table .= amici_menu_1();
	  $c1 = $row["id_ordini"];
	  $c2 = $row["descrizione_ordini"];
	  $c3 = conv_date_from_db($row["data_apertura"]);
	  $c4 = conv_date_from_db($row["data_chiusura"]);
	  $titolo_tabella = "Eliminazione ordine al quale nessuno ha partecipato";
	  
	  
	  
	  
	  $h_table .= " 
					<div class=\"ui-state-error ui-corner-all padding_6px\" style=\"margin-bottom:20px\">
					<span class=\"ui-icon ui-icon-trash\" style=\"float:left; margin:0 7px 16px 0;\"></span>
					Stai per cancellare i dati di questa scheda : sei sicuro ?
					<a href=\"ordini_chiusi_form_delete.php?id=$id&do=del\" class=\"medium red awesome\">SI</a> 
					<a href=\"ordini_chiusi_table.php\" class=\"medium green awesome\">NO</a>
					</div>
					";
		 
		 // VALORI DELLE CELLE da DB---------------------
			  
		// VALORI CELLE CALCOLATE ----------------------      
$h_table .=  "
		<div class=\"ui-widget-header ui-corner-all  padding-6px m6b\">$titolo_tabella<br> 
		<table>
		<tr class=\"odd\">
			<th $col_1>Id Ordine</th>
			<td $col_2>$c1</td>
		</tr>
		<tr  class=\"odd\">
			<th $col_1>Titolo</th>
			<td $col_2>$c2</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>Data Apertura</th>
			<td $col_2>$c3</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>Data Chiusura</th>
			<td $col_2>$c4</td>
		</tr>
		</table>
		</div>";

	  // END TABELLA ----------------------------------------------------------------------------
// $msg.= "Questa pagina non ? ancora funzionante.";
 $posizione ="ORDINI CHIUSI -> <b>Elimina</b>";	  
 include ("ordini_chiusi_main.php");
 
 
}else{
	c1_go_away("?q=no_permission");
}
?>