<?php
 
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("cassa_renderer.php");


if(isset($id_ut)){
    (int)$id_ut = mimmo_decode($id_ut);
}

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;     
}    

    //COntrollo permessi
    if(!(_USER_PERMISSIONS & perm::puo_gestire_la_cassa)){
        pussa_via();
    }

        
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender� come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men� verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar� indicata nella barra info 
    $retegas->posizione = "Situazione movimenti GAS";
      
    // Dico a retegas come sar� composta la pagina, cio� da che sezioni � composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale � pronto ma � vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = gas_menu_gestisci_cassa();
 
    // dico a retegas quali sono i fogli di stile che dovr� usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr� caricare
    $retegas->java_headers = array("rg");  
    
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion("",menu_lat::gas); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_header[]=java_tablesorter("output");
      $retegas->java_scripts_header[] = java_head_datetimepicker();
      $retegas->java_scripts_bottom_body[] = java_qtip_ajax($RG_addr["ajax_cassa_note"]);
      $retegas->java_scripts_bottom_body[] = java_datepicker("data_dal");
      $retegas->java_scripts_bottom_body[] = java_datepicker("data_al");
      //$retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

                 


       
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
       
      $err =0 ;
      if(!controllodata($data_dal)){
         $err++; 
      }
      if(!controllodata($data_al)){
         $err++; 
      }
      
      if($err==0){
          
        $filter = " WHERE data_movimento > '".conv_date_to_db($data_dal)."' AND data_movimento < '".conv_date_to_db($data_al)."' ";
          
      }else{
        unset($filter);  
      }
      
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  cassa_movimenti_gas_table(_USER_ID_GAS,$filter);
      
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);    
  
?>