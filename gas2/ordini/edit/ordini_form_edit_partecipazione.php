<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");

include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();    
}    

    //COntrollo permessi

        //COntrollo permessi

    if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
        go("ordini_form",_USER_ID,"Questa operazione ti � preclusa.","?id_ordine=$id_ordine");
        exit;
    }
    
    if(ordine_inesistente($id_ordine)){
        pussa_via();
        exit;
    }
    

    
    if($do=="mod"){
            
            $msg =     ordine_render_do_edit_partecipazione($id_ordine,$id_gas);
            if($msg=="OK"){$msg="Dati modificati correttamente";
            include("../scheda/ordini_form_main.php");
            die();
                           //include("../ordini_aperti/ordini_aperti_table.php");
                           //exit;
            }
                               
                           
    }
    

         
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Modifica la partecipazione di altri GAS";
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = ordini_menu_all($id_ordine); 
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
    
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion("",menu_lat::ordini); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

           
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      

      
      
            // QUa butto fuori chi usa EXPLORER !!!
      $h =  schedina_ordine($id_ordine)
            .ordine_render_edit_partecipazione($id_ordine,_USER_ID_GAS);
      
      
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