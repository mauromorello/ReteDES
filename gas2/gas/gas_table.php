<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi � che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  = $cookie_read[0];
	
	
	// Costruisco i menu 
	$mio_menu = des_menu_completo($id_user);
	
	// scopro come si chiama
	$usr = fullname_from_id($id_user);
	// e poi scopro di che gas � l'user
	$gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     go("sommario",_USER_ID,"Non hai i permessi necessari (Rilasciati dal tuo DES) per vedere questa pagina");
}	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender� come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men� verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar� indicata nella barra info 
	$retegas->posizione = "Gas del DES ". _USER_DES_NAME;
	  
	// Dico a retegas come sar� composta la pagina, cio� da che sezioni � composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale � pronto ma � vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = $mio_menu;
 
	// dico a retegas quali sono i fogli di stile che dovr� usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovr� caricare
	$retegas->java_headers = array("rg");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[]=java_accordion(null, menu_lat::des); // laterale    
	  $retegas->java_scripts_header[]=java_tablesorter("gas_table");
	  $retegas->java_scripts_header[]=java_superfish();
		  // orizzontale                         

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  gas_render_gas_table();

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>