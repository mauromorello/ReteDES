<?php

   
// immette i file che contengono il motore del programma

//questo � quello che contiene le funzioni di base
// vengono anche caricate le costanti che contengono tutti
//i parametri base dell'utente connesso
include_once ("../rend.php");
//questo contiene la classe per creare facilmente la pagina
include_once ("../retegas.class.php");
//questo � specifico per la sezione GAS.
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
	pussa_via();
	exit;     
}    

//controlla se l'user ha i permessi per gestire gli utenti
if(!(_USER_PERMISSIONS & perm::puo_gestire_utenti)){
    //se non li ha viene reindirizzato alla home con questo messaggio
    go("sommario",_USER_ID,"Non hai i permessi necessari");
}
	
  
	// ISTANZIO un nuovo oggetto "retegas"
	$retegas = new sito; 
	 
	// assegno la posizione che sar� indicata nella barra info 
	$retegas->posizione = "Utenti Cancellati";
	  
	// Dico a retegas come sar� composta la pagina, cio� da che sezioni � composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale � pronto ma � vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = gas_menu_completo($user);
 
	// dico a retegas quali sono i fogli di stile che dovr� usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovr� caricare
	$retegas->java_headers = array("rg");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale    
	  $retegas->java_scripts_header[]=java_tablesorter("user_table");
	  $retegas->java_scripts_header[]=java_superfish();
		  // orizzontale                         

	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  gas_render_user_deleted(_USER_ID_GAS,"user_table");

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>
