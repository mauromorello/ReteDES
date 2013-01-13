<?php


	

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");

include_once ("../ordini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
								
	// e poi scopro di che gas è l'user
	$gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	 
	// ISTANZIO un nuovo oggetto "retegas"

	$retegas = new sito; 
	$ref_table ="output";

	  // qui ci va la pagina vera e proria
	  $retegas->sezioni[]="contenuti";  
	  $retegas->content  =  ordine_render_intestazione().
							ordine_render_visualizza_ordini_aperti($id_user,$a,$ref_table);    
	  $retegas->css_body[]='word.css';    
	  $html = $retegas->sito_render_word();
	  $html = preg_replace("/<a[^>]+\>/i", "", $html);
	  $html = preg_replace("/<\/a[^>]+\>/i", "", $html);
	  $filename = "Ordini_aperti ". date("d_m_Y");
	  header("Content-Type: application/msword");
	  header ("Content-Disposition: attachment; filename=$filename");
	  echo $html;
	  
	  unset($retegas);	  
	  
	  
	  
?>
