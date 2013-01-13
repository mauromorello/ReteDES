<?php


include_once ("../../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
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
	  //$pdf_url = "oc_art_ord_pdf.php?id=$id&is_pdf=pdf";
	  //$display_url = "oc_art_ord_pdf.php?id=$id&is_pdf=screen";
	  
	  include("../ordini_aperti_menu_core.php");
	  
	  // inclusione scheda
	  // ID = ORDINE
	  
	  include ("../ordini_aperti_form_scheda.php");
	  
	  //INCLUSIONE LISTA ARTICOLI
	  
	  // c2 = id listino
	  
	  include ("oa_art_ord.php"); 
	  // HEADER HTML
	  //$msg ="Pagina non ancora funzionante";
	  $posizione ="ORDINI APERTI -> Gestione ordine -> <b>Articoli ordinati</b>";
	  $table_sorter_name = "oa_art_ord";
	  
	  include ("../ordini_aperti_main.php");
 
}else{
	pussa_via();
} 
?>