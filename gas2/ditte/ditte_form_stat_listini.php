<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("ditte_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (_USER_LOGGED_IN){

	// estraggo dal cookie le informazioni su chi � che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
								
	// e poi scopro di che gas � l'user
	$gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    
   
   if(ditta_nome($id_ditta)==""){
       pussa_via();
   }
   
   // ISTANZIO un nuovo oggetto "retegas"

	$retegas = new sito;

	$retegas->posizione = "Scheda ditta statistiche articoli";
	
 
	$ref_table ="output";


	$retegas->sezioni = $retegas->html_standard;


	$retegas->menu_sito = ditte_menu_completo($id_ditta);
	//$retegas->menu_sito[]=$h_menu;
 
	// dico a retegas quali sono i fogli di stile che dovr� usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovr� caricare
	$retegas->java_headers = array("rg");  // editor di testo
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,menu_lat::anagrafiche); // laterale    
	  $retegas->java_scripts_header[] = java_superfish(); 	  
	  $retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);


 
 
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
	  
	  
			// qui ci va la pagina vera e proria
	   
	  $retegas->content  =  ditte_render_form($id_ditta)
                            .statistiche_articoli_listini($id_ditta,$ref_table); 
		
	  $html = $retegas->sito_render();
	  echo $html;
	  exit;

	  unset($retegas);	  
  
?>