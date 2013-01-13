<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");

include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id;

// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
	$permission = $cookie_read[6];
										
	// e poi scopro di che gas è l'user
	$id_gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	//COntrollo permessi

	if(id_referente_ordine_proprio_gas($id_ordine,$id_gas)<>$id_user){
		pussa_via();
		exit;     
	}
	
	if(ordine_inesistente($id_ordine)){
		pussa_via();
		exit;
	}
	

	if($do=="mod"){
			
			$msg = 	ordine_render_do_edit_gas_spese($id_ordine,$id_gas);
			if($msg=="OK"){$msg="Dati modificati correttamente";
            include("../scheda/ordini_form_main.php");
            die();
						   //include("../ordini_aperti/ordini_aperti_table.php");
						  // exit;
			}
							   
						   
	}
		 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Crea nuovo ordine";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = ordini_menu_all($id_ordine); 
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg");  // editor di testo
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion("",3); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");
	 
		   
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
			// QUa butto fuori chi usa EXPLORER !!!
	  $h ='
			<!--[if IE]>
			<p>Purtroppo con Internet Explorer questa pagina non è visualizzabile.<br>
			   Usate un altro Browser, come <a href="http://www.google.com/chrome/index.html?hl=it">Google Chrome</a></p>
			   
			<![endif]-->
			<![if !IE]>
				'.schedina_ordine($id_ordine).'
				'.ordine_render_edit_gas_spese($id_ordine,$id_gas).'
			<noscript>
			Non è attivo Javascript su questo browser;
			</noscript>
			<![endif]>
			 
			<br>';
	  
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  $h;
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  //echo "<br>Scheda : ".(array_sum(explode(' ', microtime())) - $start);
	  
?>
