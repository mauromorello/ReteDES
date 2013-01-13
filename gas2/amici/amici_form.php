<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("amici_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
                               
    // e poi scopro di che gas ? l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;    
}    

    
   CAST_TO_INT($ida);
   
   
   if(amici_referente_di_amico($ida)<>$id_user){
       pussa_via();
       exit;    
   }
   
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Amici in rubrica";
    
      
          // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = menu_nuovo_amico($id_user);
    $retegas->menu_sito[] = menu_visualizza_amici($id_user);
    $retegas->menu_sito[] = menu_operazioni_amici($id_user,$ida);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null, menu_lat::user); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
      // qui ci va la pagina vera e proria  
      $retegas->content  =  amici_render_scheda($ida);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
    
?>