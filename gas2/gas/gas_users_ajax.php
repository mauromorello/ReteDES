<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  = $cookie_read[0];
	
	
	// Costruisco i menu 
	$mio_menu = gas_menu_completo($user);
	
	// scopro come si chiama
	$usr = fullname_from_id($id_user);
	// e poi scopro di che gas è l'user
	$id_gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 

	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	    $retegas->menu_sito = $mio_menu;

	  
	  // qui ci va la pagina vera e proria  
	  $retegas->content  =  gas_render_user($id_gas,"user_table");

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render_content();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>