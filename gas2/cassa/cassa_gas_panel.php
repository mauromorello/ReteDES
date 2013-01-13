<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("cassa_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    go("sommario");
}

    //COntrollo permessi
    if(!_USER_PERMISSIONS & perm::puo_gestire_la_cassa){
        pussa_via();
        exit;
    }    
    

    
        
    // ISTANZIO un nuovo oggetto "retegas"
    // Prenderà come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel menù verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sarà indicata nella barra info 
    $retegas->posizione = "Pannello cassa GAS";
      
    // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = gas_menu_gestisci_cassa();
 
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg");  
    
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion("",menu_lat::gas); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      //$retegas->java_scripts_header[]=java_tablesorter("output");
      //$retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

                 


       
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
     if(isset($alert)){
        $retegas->messaggio=choose_msg($alert); 
     }
     
      
      
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  cassa_gas_panel(_USER_ID_GAS);
      
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);    
  
?>