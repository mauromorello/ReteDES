<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("panoramica_functions.php");

// QUA METTO IL MENU DELLA PANORAMICA

$mio_menu[]='<li><a class="medium green awesome" href="'.$RG_addr["panoramica"].'">Breve termine</a></li>';  
$mio_menu[]='<li><a class="medium green awesome" href="'.$RG_addr["panoramica_anno"].'">Lungo Termine</a></li>';
$mio_menu[]='<li><a class="medium green awesome" href="'.$RG_addr["panoramica_anno_des"].'">Lungo Termine DES</a></li>';

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();    
}    

	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Panoramica Mese";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = $mio_menu;
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg");  // menu a scomparsa verticale
									 
	 
		   
								   
	  
	  $retegas->body_tags=" onload=\"onLoad();\" onresize=\"onResize();\"";
	  
	 
	 
	  
	  // creo  gli scripts per la gestione dei menu
	  $retegas->java_scripts_header[]=java_accordion(null,menu_lat::ordini); // laterale    
	  $retegas->java_scripts_header[]=java_superfish();    // orizzontale                         
	  $retegas->java_scripts_header[]=c1_javascript_timeline(_USER_ID); // TIMELINE
	 
	  
	  
	  // assegno l'eventuale messaggio da proporre
	  if(isset($q)){ 
		$retegas->messaggio=choose_msg($q);
	  }
	  
      $h="<div class=\"rg_widget rg_widget_helper\">
        <h3>Panoramica ordini</h3>";    
        $h .='<div id="my-timeline" style="height:400; border: 1px solid #aaa"></div>
         
        </div>';
      
		  // qui ci va la pagina vera e proria  
	  $retegas->content =  $h;
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  
      //FORZO LA VECCHIA CODIFICA
      echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"> ";
      echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  