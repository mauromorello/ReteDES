<?php


include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);
	
	 if(ordine_io_cosa_sono($id,$id_user)==0){
		pussa_via();       
		exit;    
		}
		
if(stato_from_id_ord($id)<>2){
		pussa_via();         
		exit; 
		}
if(ordine_inesistente($id)){
		pussa_via();         
		exit;    
	
}
	 // --->ID
	 // ---- h_table
	 // ---- msg
	 // --- menu aperto
	
	  // MENU APERTO
	  $menu_aperto=3;
	   
	  // Campi e intestazioni
	  include ("ordini_aperti_sql_core.php");
	  
	  // menu	  
	  include("ordini_aperti_menu_core.php");
	  
	  // inclusione scheda
	  // ID = ORDINE
	  
	  include ("ordini_aperti_form_scheda.php");
	  
	  
	  
	  //INCLUSIONE LISTA ARTICOLI
	  
	  // c2 = id listino
	  //$ido=$id;
	  //$id=$c2;
	  //include ("../articoli/articoli_table_core.php");
	  
	  $qry="SELECT * FROM retegas_messaggi
	  WHERE
	  retegas_messaggi.tipo LIKE 'ORD%'
	  AND
	  retegas_messaggi.id_ordine = '$id'
	  ORDER BY
	  retegas_messaggi.timbro DESC";     
$res = $db->sql_query($qry);

$hi = "<div class=\"ui-widget-header ui-corner-all padding_6px\" style = \"margin-bottom:6px;\">Cronologia ordine</div>";

while ($row = mysql_fetch_array($res)){

$quando = conv_datetime_from_db($row[4]);    
$hi .= "<div class=\"ui-widget ui-corner-all\" style=\"color:#808080\">
		<b style=\"color:#636363\">$quando</b>, $row[3]</div>";     
	
}
$h_table.=$hi;  
	 
	  // HEADER HTML
	  //$msg ="Pagina non ancora funzionante";
	  $posizione = "ORDINI APERTI -> Gestione -> Cronologia -> <b>Completa</b>";
	  include ("ordini_aperti_main.php");
 
}else{
	pussa_via();
} 
?>
