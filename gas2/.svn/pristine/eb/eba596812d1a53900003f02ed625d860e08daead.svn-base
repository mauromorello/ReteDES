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

	// estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
	$permission = $cookie_read[6];
										
	// e poi scopro di che gas ? l'user
	$id_gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	//COntrollo permessi

		//COntrollo permessi

	if(id_referente_ordine_globale($id_ordine)<>$id_user){
		pussa_via();
		exit;     
	}
	
	if(ordine_inesistente($id_ordine)){
		pussa_via();
		exit;
	}
	
	$res = $db->sql_query("SELECT * FROM retegas_ordini WHERE id_ordini='$id_ordine' LIMIT 1");
	$old_row = $db->sql_fetchrow($res);
	$old_data_apertura = conv_datetime_from_db($old_row["data_apertura"]); 
	$old_data_chiusura = conv_datetime_from_db($old_row["data_chiusura"]);
	
	if($do=="mod"){
			
			$msg = 	ordine_render_do_edit_date($id_ordine);
			if($msg=="OK"){$msg="Dati modificati correttamente";
            include("../scheda/ordini_form_main.php");
            die();
						  // include("../ordini_aperti/ordini_aperti_table.php");
						  // exit;
			}
							   
						   
	}
	
	
	$data_chiusura = $old_data_chiusura;
	$data_apertura = $old_data_apertura;
		 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Modifica le scadenze";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = ordini_menu_all($id_ordine); 
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("jquery",      // librerie universali
									 "jquery_ui",
									 "accordion",
									 "datetimepicker",
									 "datepicker_loc",   // aggiunta per effetti e transizioni
									 "superfish",
									 "qtip");  // editor di testo
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion("",3); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");
	  $retegas->java_scripts_bottom_body[] = c1_ext_javascript_datetimepicker("#datetimepicker","#alt1");
	  $retegas->java_scripts_bottom_body[] = c1_ext_javascript_datetimepicker("#datetimepicker2"); 
	  
		   
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  

	  
	  
			// QUa butto fuori chi usa EXPLORER !!!
	  $h =  schedina_ordine($id_ordine)
			.ordine_render_edit_date($id_ordine);
	  
	  
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
