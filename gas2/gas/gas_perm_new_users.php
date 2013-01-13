<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    die;    
}    

if(!(_USER_PERMISSIONS & perm::puo_gestire_utenti)){
	pussa_via();
	exit;	
}	

if($do=="change_default_permissions"){
            
                
                
                $UP =   (int)$p_c_o |             //1
                        (int)$p_p_o |             //2
                       // (int)$p_c_g |             //4
                        (int)$p_c_d |             //8
                        (int)$p_c_l |             //16
                       // (int)$p_m_p |             //32
                        (int)$p_a_a |             //64
                        (int)$p_s_b |             //128
                       // (int)$p_e_m |             //256
                       // (int)$i_n_u |             //512
                       // (int)$p_v_t_o |           //1024
                        (int)$p_o_c ;             //2048
                       // (int)$p_v_r_g;            //4096
                
                
                //echo "UP : ".$UP."<br>";
              
                $db->sql_query("UPDATE retegas_gas SET default_permission = '$UP' WHERE id_gas='"._USER_ID_GAS."'");
    
                $msg="Permessi modificati";
                unset($do);
	
}
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Permessi di default";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = $mio_menu;
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("rg");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
		  
	   
	  $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale    
	  //$retegas->java_scripts_header[]=java_tablesorter("gas_table");
	  $retegas->java_scripts_header[]=java_superfish();
		  // orizzontale                         

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		  // qui ci va la pagina vera e proria  
	  
      
      $user_permission = leggi_permessi_default(_USER_ID_GAS);

      
	  $retegas->content  =  //gas_render_scheda($gas) .
                            gas_permessi_default($user_permission,"gas_perm_new_users.php");

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>