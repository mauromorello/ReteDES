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
	  include ("../ordini_chiusi_sql_core.php");
	  
	  // menu
	  if(empty($id_g)){
		  $id_g = $gas;
	  }
	  
	  $pdf_url = "oc_g_art_pdf.php?id=$id&id_g=$id_g&is_pdf=pdf";
	  $display_url = "oc_g_art_pdf.php?id=$id&id_g=$id_g&is_pdf=screen";
	  
	  include("../ordini_chiusi_menu_core.php");
	  
	  // inclusione scheda
	  // ID = ORDINE
	  
	  include ("../ordini_chiusi_form_scheda.php");
	  
	  //INCLUSIONE LISTA ARTICOLI
	  
	  // c2 = id listino
	  
	  // controllo che sia il referente del mio GAS
	  // SONO REFERENTE ORDINE
	  //echo $id,$id_g,$id_user,id_referente_ordine_globale($id);
	  if($id_user==id_referente_ordine_globale($id)){
		  
	  }else{

			unset($id_g);  
		  
	  }
	  
	  
	  include ("oc_g_art.php"); 
	  // HEADER HTML
	  //$msg ="Pagina non ancora funzionante";
	  include ("../ordini_chiusi_main.php");
 
}else{
	pussa_via();
} 
?>
