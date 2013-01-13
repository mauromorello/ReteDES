<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
	pussa_via();
	exit;     
}    


	// se l'id dell'ordine non esiste allora esco
	if(ordine_inesistente($id)){
			pussa_via();         
			exit;   	
		}

	if (id_referente_ordine_globale($id)<>_USER_ID){
		$msg = "Birbante, stai cercando di modificare un ordine non tuo";
		include ("../ordini_aperti/ordini_aperti_table.php");
	exit;    
	}

	if ($do=="mod"){
		unset($do);
		$error = ordine_check_data($id);
		if($error==""){
			  
			$where = ordine_do_mod($id);
			$msg = "Ordine modificato correttamente";
			if($where==1){
				include ("../ordini_aperti/ordini_aperti_form.php");
				exit;
			}
			if($where==2){
				include ("../ordini_aperti/ordini_aperti_form.php");
				exit;      
			}
			if($where==3){
				include ("../ordini_chiusi/ordini_chiusi_form.php");
				exit;
			}
			
			$msg = "Problemi nella modifica dell'ordine";
			include ("../ordini/ordini_form_edit.php");
			exit;
			
		}else{
			
			$msg = $error;
			include ("../ordini/ordini_form_edit.php");
			exit;       
		}
		
		
	}
	
	
	
	$query_visual = "SELECT * FROM retegas_ordini 
						WHERE  (id_ordini='$id') LIMIT 1";
	$result = $db->sql_query($query_visual);
	$row = $db->sql_fetchrow($result);
	
	//assegno ai campi i loro valori
	$o_old_data_apertura = conv_datetime_from_db($row["data_apertura"]);
	$o_old_data_chiusura = conv_datetime_from_db($row["data_chiusura"]);
	
	if(empty($o_descrizione)){$o_descrizione = $row["descrizione_ordini"];}
	$o_data_apertura = conv_datetime_from_db($row["data_apertura"]);
	$o_data_chiusura = conv_datetime_from_db($row["data_chiusura"]);
	if(empty($o_costo_gestione)){$o_costo_gestione = $row["costo_gestione"];}
	if(empty($o_costo_trasporto)){$o_costo_trasporto = $row["costo_trasporto"];}
	if(empty($o_note_ordine)){$o_note_ordine = $row["note_ordini"];}
	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Modifica Ordine";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = ordini_menu_all($id_ordine); 
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	$retegas->css[]  = "datetimepicker"; 
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("jquery",      // librerie universali
									 "jquery_ui",
									 "accordion",
									 "datepicker",   // aggiunta per effetti e transizioni
									 "superfish",   // menu a scomparsa orizzontale
									 "ckeditor",
									 "datetimepicker",
									 "datepicker_loc");  // editor di testo
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  
	  
		  // orizzontale                         

	  $retegas->java_scripts_bottom_body[] = c1_ext_javascript_datetimepicker("#datetimepicker");
	  $retegas->java_scripts_bottom_body[] = c1_ext_javascript_datetimepicker("#datetimepicker2");
		   
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  ordine_render_modifica($id)."<br>";
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>