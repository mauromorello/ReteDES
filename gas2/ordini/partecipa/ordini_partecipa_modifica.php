<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");



if(isset($id)){$id_ordine=$id;}; 
(int)$id_ordine;


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
    $permission =  $cookie_read[6];                            
    // e poi scopro di che gas è l'user
    $id_gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;     
}    
   // ISTANZIO un nuovo oggetto "retegas"

   
  
    $retegas = new sito;

    $retegas->posizione = "Modifica merce ordinata";

    
    //CONTROLLO SE L'ORDINE E' PARTECIPABILE DA ME
    if(!($permission & perm::puo_partecipare_ordini)){
            pussa_via();;
            exit;            
            }

    if(ordine_io_cosa_sono($id_ordine,$id_user)==0){
            //echo "ORDINE CHE NON MI COMPETE";
            pussa_via();       
            exit;    
    }
    
    
    if(ordine_inesistente($id_ordine)){
        pussa_via();
        exit;    
    }
    
    if(!ordine_partecipabile($id_ordine)){
        pussa_via();
        exit;        
    }
    
    
    
    
    

    // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    
    // Menu specifico per l'output  

    
    $retegas->menu_sito = ordini_menu_all($id_ordine);
    
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
    //$retegas->css[]  = "datetimepicker"; 
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg","jcalc");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $ref_table = "partecipa";
      
      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      $retegas->java_scripts_header[]=  java_tablesorter($ref_table);
      $retegas->java_scripts_header[]=  java_head_fg_menu();
      
      $retegas->css_body[] = fg_css();
      
      
      $retegas->java_scripts_bottom_body[] = java_qtip_ajax($RG_addr["ajax_articoli_note"]);

                                          
                                                     
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
            // qui ci va la pagina vera e proria  
      $retegas->content  =  schedina_ordine($id_ordine).
                            ordine_render_partecipa($ref_table,$id_ordine,$id_user);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas); 