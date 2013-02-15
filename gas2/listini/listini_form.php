<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("listini_renderer.php");





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
   // ISTANZIO un nuovo oggetto "retegas"

   if(listino_is_privato($id)){
        if(id_gas_user(listino_proprietario($id))<>_USER_ID_GAS){
          //FIXME go("sommario",_USER_ID,"Listino privato");
       }
   }
   
   if($do=="upload"){
            if($tipo_file=="CSV"){  
                
                $msg = do_upload($fname,$listino);
                unset($do);
                $id=$listino;
                
                include("listini_form.php");
                exit;
            }
      
            if($tipo_file=="XLS"){  
                $msg = do_upload_xls($fname,$listino);
                unset($do);
                $id=$listino;
                include("listini_form.php");
                exit;
            }
            
            if($tipo_file=="GOO"){  
                
                //http://retegas.altervista.org/gas2/listini/listini_form.php?do=upload&fname=https%3A%2F%2Fdocs.google.com%2Fspreadsheet%2Fpub%3Fkey%3D0An0LoUdzBJs0dDZ4UENCSVpvZ21yWVhaUHRla1JaVkE%26output%3Dcsv&listino=67&tipo_file=GOO&quanti_caricarne=9
                $msg = do_upload_goo(urldecode($fname),$listino,$quanti_caricarne);
                unset($do);
                $id=$listino;
                include("listini_form.php");
                exit;
            }
            
      }

    
    
    
    
    $retegas = new sito;
    
    


    $retegas->posizione = "Scheda Listino";
    

     
     
     $ref_table ="output";

      
    // assegno la posizione che sar? indicata nella barra info 
    
    
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
    
        
    $retegas->menu_sito[]=listini_menu_completo($id);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
    
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null,menu_lat::anagrafiche); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      $retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
      //$retegas->java_scripts_bottom_body[] = java_qtip();
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      $retegas->messaggio .= read_option_text($id_user,"MSG");
                             delete_option_text($id_user,"MSG");
      
      
      
            // qui ci va la pagina vera e proria
       
      $retegas->content  =   listini_form($id,false).
                             "<div id=\"container_articolo\"></div>"   
                            .listini_articoli_table($ref_table,$id);  
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
      
?>