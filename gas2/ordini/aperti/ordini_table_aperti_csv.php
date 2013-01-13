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

	   $output_file = 'Ordini_aperti_'.date("d_m_Y").'.csv'; 
		@ob_end_clean(); 
		@ini_set('zlib.output_compression', 'Off'); 
		header('Pragma: public'); 
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT'); 
		header('Cache-Control: no-store, no-cache, must-revalidate'); 
		header('Cache-Control: pre-check=0, post-check=0, max-age=0'); 
		header('Content-Transfer-Encoding: none'); 
		//This should work for IE & Opera 
		header('Content-Type: application/octetstream; name="' . $output_file . '"'); 
		//This should work for the rest 
		header('Content-Type: application/octet-stream; name="' . $output_file . '"'); 
		header('Content-Disposition: inline; filename="' . $output_file . '"'); 
		echo ordine_render_visualizza_ordini_aperti($id_user,$a,$ref_table,"ON"); 


	  unset($retegas);	  
	  
	  
	  
?> 