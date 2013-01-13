<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("utenti_render.php");

 
// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
    $permission = $cookie_read[6];
                                        
    // e poi scopro di che gas ? l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;     
}    

    if($do=="pwd"){
          //check empty fields
         if((empty($old_pwd)) or (empty($new_pwd1)) or (empty($new_pwd2))){
          $msg = "Tutti i campi devono essere compilati";
          unset($do);
          include ("utenti_form_passw.php");
          exit;
         }
         
         $cookie_read = explode("|", base64_decode($user));
         $userid = $cookie_read[0];
         
         $old_pwd_md5 = md5($old_pwd);
         $result = $db->sql_query("SELECT userid,password FROM maaking_users WHERE userid='$userid' AND password='$old_pwd_md5'");

         if($db->sql_numrows($result) == 0){
         
          $msg = "La vecchia password non è stata riconosciuta";
          unset($do);
          include ("utenti_form_passw.php");
          exit;
               
         }else{

               if($new_pwd1 != $new_pwd2){

                     $msg = "La password nuova non è stata ripetuta correttamente";
                     unset($do);
                     include ("utenti_form_passw.php");
                     exit;
                     
               }else{

                     $md5_password = md5($new_pwd1);
                     $sql = $db->sql_query("UPDATE maaking_users SET password='$md5_password' WHERE userid='$userid'");

                    
                     unset($do);
                     $q="chg_passw";
                     
                     header("Location: ".$RG_addr["sommario"]."?q=chg_passw");
                     exit;
               }
         }
          
          
          unset($do);
          include ("utenti_form.php");
          exit;
      }
         
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Modifica la tua password";
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = menu_visualizza_user($id_user);
    $retegas->menu_sito[] = menu_gestisci_user($id_user,$id);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array( "rg");  // editor di testo
    
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

          // orizzontale                         

      
           
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
        
      //$h =ordine_render_add_simple($id);
      
      
      // qui ci va la pagina vera e proria  
      $retegas->content  =  utenti_render_form_password($id_user);
      
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);      
      
      
      
?>