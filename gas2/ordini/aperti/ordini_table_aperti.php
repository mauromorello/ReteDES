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

if($do=="bl"){
    $id_ordine=mimmo_decode($id_ordine);
    if(ordine_inesistente($id_ordine)){
        $msg= "Ordine inesistente";    
    }else{
        $msg= "Ordine nascosto";
        write_option_order_blacklist(_USER_ID_GAS,$id_ordine);
        log_me($id_ordine,_USER_ID,"BLL","","Added to BL");
    }
    
}
	 
	// ISTANZIO un nuovo oggetto "retegas"

	$retegas = new sito; 
	$ref_table ="output";

	  
	  // assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Tabella Ordini aperti";
	  
		  // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito[] = ordini_menu_visualizza($user);
	$retegas->menu_sito[] = ordini_menu_nuovo($user);
	//$retegas->menu_sito[] = ordini_menu_oa_esporta($user);
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	//$retegas->css[]  = "datetimepicker"; 
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg",      // librerie universali
									"progression");  // editor di testo
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,menu_lat::ordini); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  //$retegas->java_scripts_header[] = java_sparkline(1000);
      //$retegas->java_scripts_header[] = java_sparkline($sparkline_rangemax);
	  //$retegas->java_scripts_bottom_body[] = java_sparkline_pie(".sparkline_pie");
	  //$retegas->java_scripts_header[] = java_head_jquery_metadata();  
	  
	  $retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
	  
	  $retegas->java_scripts_bottom_body[] = java_qtip();
	  $retegas->java_scripts_bottom_body[] = java_progression(".progressbar"); 
	  // assegno l'eventuale messaggio da proporre
	  
      
      
            // assegno l'eventuale messaggio da proporre
      if(isset($msg)){
        switch ((int)$msg){
            case 1:
            $msg = "Ordine eliminato correttamente";
            break;
            case 2:
            $msg = "Ordine programmato correttamente";
            break;
            case 3:
            $msg = "Ordine programmato correttamente";
            break;                  
        }
      }
      if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
	  
	  
			// qui ci va la pagina vera e proria  
	  $retegas->content  =  ordine_render_visualizza_ordini_aperti_full($ref_table);
		
	  $html = $retegas->sito_render();
	  echo $html;
	  exit;

	  unset($retegas);