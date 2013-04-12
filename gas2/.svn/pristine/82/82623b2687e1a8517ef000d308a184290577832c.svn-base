<?php

function ordine_render_panel_gestisci($id_user,$id_ordine,$id_gas){
	global $db;
	global $RG_addr;
	
	if(isset($id_ordine)){

	//OPERAZIONI COMUNI
	$cambia_spese_gas = '<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["edit_spese_gas"].'?id_ordine='.$id_ordine.'">Costi (proprio GAS)</a>';
	$cambia_costi = '<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["edit_costi"].'?id_ordine='.$id_ordine.'">Costi (Generali)</a>';	
	$cambia_descrizione ='<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["edit_descrizione"].'?id_ordine='.$id_ordine.'">Descrizioni</a>';
	$cambia_gas_coinvolti ='<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["edit_partecipazione"].'?id_ordine='.$id_ordine.'">Gas Coinvolti</a>';
	$cambia_date ='<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["edit_scadenze"].'?id_ordine='.$id_ordine.'">Date e scadenze</a>';
		
	if(stato_from_id_ord($id_ordine)==3){
	
	$report_gas = '<a class="medium green awesome" style="display:block; margin:0.5em;" href="'.$RG_addr["rep_situazione_gas"].'?id='.$id_ordine.'" target="_self">Situazione GAS</a>';        
	$report_dettaglio_articoli = '<a class="medium green awesome" style="display:block; margin:0.5em;" href="'.$RG_addr["rep_dettaglio_articoli"].'?id='.$id_ordine.'" target="_self">Dettaglio Articoli (tutti i gas)</a>';        
				
	
		if(is_printable_from_id_ord($id_ordine)){
			$convalida .='<a class="medium blue awesome" style="display:block; margin:0.5em;" href="'.$RG_addr["convalida_ordine"].'?id='.$id_ordine.'&do=allow_print" target="_self">Togli Convalida</a>';    
			$show_modifica="OFF";
		
		}else{
			//ORDINE CHIUSO MA NON CONVALIDATO
			$convalida ='<a class="medium celeste awesome" style="display:block; margin:0.5em;" href="'.$RG_addr["convalida_ordine"].'?id='.$id_ordine.'&do=allow_print" target="_self">Convalida</a>';        
			$cambia_listino ='<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["edit_switch_listino"].'?id_ordine='.$id_ordine.'" target="_self">Cambia listino</a>';            
			$rettifica_totali='<a class="medium blue awesome" style="display:block; margin:0.5em;" href="'.$RG_addr["rettifica_totali"].'?id='.$id_ordine.'" target="_self">Rettifica (totali)</a>';
			$rettifica_users='<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["rettifica_users"].'?id='.$id_ordine.'" target="_self">Rettifica (Singolo utente)</a>';
			$rettifica_denaro='<a class="awesome medium blue" style="display:block; margin:0.5em;" href="'.$RG_addr["rettifica_denaro"].'?id='.$id_ordine.'" target="_self">Rettifica (Importi)</a>';
		}
	
	
	}
	

	
	if($id_user==id_referente_ordine_globale($id_ordine)){
		//REFERENTE GLOBALE 
	}else{
		//REFERENTE GAS 
		unset($cambia_costi);
		unset($cambia_descrizione);
		unset($cambia_gas_coinvolti);
		unset($cambia_date);
		unset($cambia_listino);
		   
	}
	
	
	if($show_modifica<>"OFF"){   
	$h.='<div class="ui-corner-all" style="display:inline-block ;border: 1px solid #D0D0D0; width:20%; padding:0.5em; margin:0.5em; vertical-align:top;">
		 <span style="font-weight:bold; text-align:middle; font-size:1.1em;">Modifica :</span>
		 '.$cambia_spese_gas.'
		 '.$cambia_costi.'
		 '.$cambia_descrizione.'
		 '.$cambia_gas_coinvolti.'
		 '.$cambia_date.'
		 </div>';
	}	 
	$h.='<div class="ui-corner-all" style="display:inline-block ;border: 1px solid #D0D0D0; width:20%; padding:0.5em; margin:0.5em; vertical-align:top;">
		 <span>Operazioni</span>
		 '.$cambia_listino.'
		 '.$rettifica_totali.'
		 '.$rettifica_users.'
		 '.$rettifica_denaro.'  
		 '.$convalida.' 
		 </div>
		 
		 
		 <div class="ui-corner-all" style="display:inline-block ;border: 1px solid #D0D0D0; width:20%; padding:0.5em; margin:0.5em; vertical-align:top;">
		 <span>Reports</span>
		 '.$report_gas.'
		 '.$report_dettaglio_articoli.' 
		 <a class="awesome medium blue" style="display:block; margin:0.5em;">Dettaglio articoli (totale)</a>  
		 <a class="awesome medium blue" style="display:block; margin:0.5em;">Per Fornitore (scatole intere)</a>
		 </div>
		
		<div class="ui-corner-all" style="display:inline-block ;border: 1px solid #D0D0D0; width:20%; padding:0.5em; margin:0.5em; vertical-align:top;">
		 <span>Fornitore</span>
		 '.$report_gas.'
		 '.$report_dettaglio_articoli.' 
		 <a class="awesome medium blue" style="display:block; margin:0.5em;">Dettaglio articoli (totale)</a>  
		 <a class="awesome medium blue" style="display:block; margin:0.5em;">Per Fornitore (scatole intere)</a>
		 </div>
		
		<div class="ui-corner-all" style="display:inline-block ;border: 1px solid #D0D0D0; width:20%; padding:0.5em; margin:0.5em; vertical-align:top;">
		 <span>Speciali</span>
		 <a class="awesome medium blue" style="display:block; margin:0.5em;">Avanzo - Ammanco</a> 
		</div>
		';
   
	}     
		
	return $h;    
		
	}
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
								
	// e poi scopro di che gas ? l'user
	$id_gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    


if(!isset($id_ordine)){
		pussa_via();
		exit;    
}
	
	
if((id_referente_ordine_globale($id_ordine)<>$id_user) OR id_referente_ordine_proprio_gas($id_ordine,$id_gas)<>$id_user){
		pussa_via();
		exit;    
} 	
	 
	// ISTANZIO un nuovo oggetto "retegas"

	$retegas = new sito; 

	  
	  // SE E' LA VISUALIZZAZIONE NORMALE;
	  
	  // assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Pannello Gestione ordine";
	  
		  // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = ordini_menu_all($id_ordine); 
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	//$retegas->css[]  = "datetimepicker"; 
	  
	// dico a retegas quali file esterni dovr? caricare rg
	$retegas->java_headers = array("jquery",      // librerie universali
									 "jquery_ui",
									 "accordion",
									 "superfish",
									 "qtip");  // editor di testo 
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,3); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_qtip();
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
	  
	  
	  // qui ci va la pagina vera e proria  
	  $retegas->content  =  schedina_ordine($id_ordine)
							.ordine_render_panel_gestisci($id_user,$id_ordine,$id_gas);
	  
		
	  $html = $retegas->sito_render();
	  echo $html;
	  exit;

	  unset($retegas);	  
	  
	  
	  
?>
