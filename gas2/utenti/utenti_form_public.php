<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
                               
    // e poi scopro di che gas è l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;    
}    
        
        $id_utente = mimmo_decode($id_utente);

        
      if($do=="send_mail"){

                      
          $da_chi = fullname_from_id($id_user);
          $mail_da_chi = id_user_mail($id_user);
        
          $verso_chi = fullname_from_id($id_utente); 
          $mail_verso_chi = id_user_mail($id_utente);
            
          $soggetto = "[RETEGAS AP] - da $da_chi - Comunicazione";
          $result = manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$msg_mail);
          
            
          $msg="Mail inviata a $verso_chi";
          
          unset($do);

      }
      
      if((_USER_PERMISSIONS & perm::puo_gestire_utenti)){

        
      
      if($do=="susp"){
        if(isset($id_utente)){         
        $id_utente = CAST_TO_INT(($id_utente));
        $sql = "UPDATE maaking_users SET isactive=2 WHERE userid='$id_utente' LIMIT 1;";
        $res = $db->sql_query($sql);
        go("user_note_suspended",_USER_ID,"Inserisci un messaggio per avvisarlo quando si collega","?id_utente=".mimmo_encode($id_utente));
        }
      }
      if($do=="act"){
        if(isset($id_utente)){         
        $id_utente = CAST_TO_INT(($id_utente));
        $sql = "UPDATE maaking_users SET isactive=1, last_activity=NOW()  WHERE userid='$id_utente' LIMIT 1;";
        $res = $db->sql_query($sql);
        $msg = "Utente attivato";
        }
      }
      if($do=="del"){
        if(isset($id_utente)){         
        $id_utente = CAST_TO_INT(($id_utente));
        $sql = "UPDATE maaking_users SET isactive=3 WHERE userid='$id_utente' LIMIT 1;";
        $res = $db->sql_query($sql);
        $msg = "Utente eliminato";
        }
      }
      if($do=="stb"){
        if(isset($id_utente)){         
        $id_utente = CAST_TO_INT(($id_utente));
        $sql = "UPDATE maaking_users SET isactive=0 WHERE userid='$id_utente' LIMIT 1;";
        $res = $db->sql_query($sql);
        $msg = "Utente rimesso in attesa di accettazione.";
        }
      }
      
      }  
          
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sarà indicata nella barra info 
    $retegas->posizione = "Scheda Pubblica";
      
          // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    
    $retegas->menu_sito[] = menu_visualizza_user(_USER_ID);
    $retegas->menu_sito[] = menu_gestisci_user(_USER_ID,$id_utente);
    $retegas->menu_sito[] = menu_gestisci_user_cassa(_USER_ID,$id_utente); 
   
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg","ckeditor");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
      // qui ci va la pagina vera e proria  
      $retegas->content  =  utenti_form_public($id_utente,$gas);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
    
?>