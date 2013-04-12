<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("amministra_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//controlla se l'utente ha i permessi necessari
if(!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}
    
if($do=="do_change"){
   if(isset($gasappartenenza)){
       if((int)($id_utente_target)>0){    
         (int)$gasappartenenza;
         $sql="UPDATE maaking_users SET id_gas='$gasappartenenza' WHERE userid='".$id_utente_target."' LIMIT 1;";
         $res = $db->sql_query($sql);
         if($res == TRUE ){ 
             $messaggio = "L'utente ".fullname_from_id($id_utente_target). " si è convertito al gas $gasappartenenza (".gas_nome($gasappartenenza)."), prima apparteneva al GAS "._USER_ID_GAS." (".gas_nome(_USER_ID_GAS).")";
         }else{
             $messaggio = "L'utente ".fullname_from_id($id_utente_target). " voleva convertirsi al gas $gasappartenenza ma c'è stato un disguido.";
         }
         go("sommario",_USER_ID,$messaggio); 
       }
   } 
    
}
   
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Infiltrazione in un altro GAS";
      
          // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = amministra_menu_completo($id_user);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null, menu_lat::user); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      $retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
            // qui ci va la pagina vera e proria  
      $retegas->content  =  amministra_infiltrati();
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
     
?>