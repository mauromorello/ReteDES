<?php


include_once ("../../rend.php");
//include_once ("../../ordini/ordini_renderer.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$id_gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);
	
	 // --->ID
	 // ---- h_table
	 // ---- msg
	 // --- menu aperto
	
	  // MENU APERTO
	  $menu_aperto=3;
	   
	  // Campi e intestazioni
	  include ("../ordini_aperti_sql_core.php");
	  
	  // menu	  
	  include("../ordini_aperti_menu_core.php");
	  
	  // inclusione scheda
	  // ID = ORDINE
	  $h_table .= "<hr>";
	  $h_table .= schedina_ordine($id);
	  //include ("../ordini_aperti_form_scheda.php");
	  
	  //INCLUSIONE LISTA ARTICOLI
	  
	  // c2 = id listino
	  
	  include ("mia_spesa.php"); 
	  // HEADER HTML
	  //$msg ="Pagina non ancora funzionante";
	  $posizione ="ORDINI APERTI -> Mia spesa -> <b>Dettaglio assegnazioni</b>";
	  include ("../ordini_aperti_main.php");
 
}else{
	pussa_via();
} 
?>
