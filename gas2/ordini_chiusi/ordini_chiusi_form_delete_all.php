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
		//controllo di essere ZEUS
		
		if (user_level($id_user)<5){
			$msg="Sembra che tu non abbia i privilegi necessari per questa operazione";
			include("ordini_chiusi_table.php");    
			exit;       
		}
		
		// non ci sono altri controlli in quanto questa operazione distrugge tutto l'ordine
		
  
	  //-------------------------------------------------DELETE
		if($do=="del_all"){
		$nomordine = descrizione_ordine_from_id_ordine($id);  	
		$msg = "";    
		// eliminazione tabella ORDINI
		if($el_ordini=="ON"){
			//echo "ELIMINAZIONE ORDINE  -> $el_ordini<br>";
			$msg .="Tolto da tabella ORDINI<br>";
			$sql = $db->sql_query("delete from  retegas_ordini where retegas_ordini.id_ordini=$id LIMIT 1;");
		}
		// eliminazione tableea DETTAGLI
		if($el_dettagli=="ON"){ 
			//echo "ELIMINAZIONE DETTAGLI -> $el_dettagli<br>";
			$sql = $db->sql_query("delete FROM retegas_dettaglio_ordini WHERE id_ordine= '$id';");
			$msg .="Tolto da tabella DETTAGLI<br>";  
		}    
		// eliminazione tableea ASSEGNAZIONI
		if($el_assegnazioni=="ON"){ 
			//echo "ELIMINAZIONE ASSEGNAZIONI -> $el_assegnazioni<br>";
			$sql = $db->sql_query("delete FROM retegas_distribuzione_spesa WHERE id_ordine= '$id';");
			$msg .="Tolto da tabella ASSEGNAZIONI<br>"; 
		}
			 
		// eliminazione dalla tabella MESSAGGI
		if($el_messaggi=="ON"){ 
			//echo "ELIMINAZIONE MESSAGGI -> $el_messaggi<br>";
			$sql = $db->sql_query("delete FROM retegas_messaggi WHERE id_ordine= '$id';");
			$msg .="Tolto da tabella MESSAGGI<br>";  
		}
		
		if($el_referenze=="ON"){ 
			//echo "ELIMINAZIONE REFERENZE -> $el_referenze<br>";
			$sql = $db->sql_query("delete FROM retegas_referenze WHERE id_ordine_referenze= '$id'");
			$msg .="Tolto da tabella REFERENZE<br>";  
		}
			
			
		log_me($id,$id_user,"ORD","ERA","Eliminazione globale ordine $id, ($nomordine)",0,$msg);
	
		unset($do);
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
	  $titolo_tabella = "Eliminazione ordine GLOBALE";
	  
	  $total_ordini = ceil(mysql_num_rows(mysql_query("SELECT id_ordini FROM retegas_ordini WHERE id_ordini= '$id'")));
	  $total_dettagli = ceil(mysql_num_rows(mysql_query("SELECT id_dettaglio_ordini FROM retegas_dettaglio_ordini WHERE id_ordine= '$id'"))); 
	  $total_assegnazioni = ceil(mysql_num_rows(mysql_query("SELECT id_distribuzione FROM retegas_distribuzione_spesa WHERE id_ordine= '$id'"))); 
	  $total_messaggi = ceil(mysql_num_rows(mysql_query("SELECT id_messaggio FROM retegas_messaggi WHERE id_ordine= '$id'"))); 
	  $total_referenze = ceil(mysql_num_rows(mysql_query("SELECT id_referenze FROM retegas_referenze WHERE id_ordine_referenze= '$id'"))); 
	  
	  $h_table .= ' 
					<div class="ui-state-error ui-corner-all padding_6px" style="margin-bottom:20px">
					<form name="delete_all" action="ordini_chiusi_form_delete_all.php" method="post" target="_self">
					<ul>
					<li><input type="checkbox" name="el_ordini" id="ordini" value="ON" align="left" checked  title="Tabella Ordini">TABELLA ORDINI ('.$total_ordini.' record)</li>
					<li><input type="checkbox" name="el_dettagli" id="dettagli" value="ON" align="left" checked  title="Tabella DETTAGLI">TABELLA DETTAGLI ('.$total_dettagli.' record)</li>
					<li><input type="checkbox" name="el_assegnazioni" id="assegnazioni" value="ON" align="left" checked  title="Tabella Assegnazioni">TABELLA ASSEGNAZIONI ('.$total_assegnazioni.' record)</li>
					<li><input type="checkbox" name="el_messaggi" id="messaggi" value="ON" align="left" checked  title="Tabella Messaggi">TABELLA MESSAGGI ('.$total_messaggi.' record)</li>
					<li><input type="checkbox" name="el_referenze" id="referenze" value="ON" align="left" checked  title="Tabella Referenze">TABELLA REFERENZE ('.$total_referenze.' record)</li>   
					</ul>
					<input type="hidden" name="do" id="do" value="del_all">
					<input type="hidden" name="id" id="id" value="'.$id.'">
					<input type="submit" name="submit" id="submit" value="CANCELLA" align="right"  class="awesome">
					</form> 
					</div>
					';
		 
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