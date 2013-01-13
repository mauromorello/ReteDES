<?php



   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("../storici/storici_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
								
	// e poi scopro di che gas ? l'user
	$gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    
   // ISTANZIO un nuovo oggetto "retegas"

	$retegas = new sito;
	

	$retegas->posizione = "Storico Ditte su GAS";
	// assegno la posizione che sar? indicata nella barra info 
	
	
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	

	//$retegas->menu_sito = storici_menu_completo();
	
    // dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;

	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("rg","metadata","datepicker_loc");  // editor di testo
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,3); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
 	  
      $retegas->java_scripts_bottom_body[] = java_tablesorter("miei_ordini");
      $retegas->java_scripts_bottom_body[] = java_datepicker("dat_1");
      $retegas->java_scripts_bottom_body[] = java_datepicker("dat_2");
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
	  if($do=="filter"){
          sanitize($filter);
          if($filter=="date"){    
          $arg1 = sanitize($data_da);
          $arg2 = sanitize($data_a);
        }   
      }
	  
			// qui ci va la pagina vera e proria  
	  $retegas->content  =  storici_ditte_des($id_user,$filter,$arg1,$arg2);
		
	  $html = $retegas->sito_render();
	  echo $html;
	  exit;

	  unset($retegas);