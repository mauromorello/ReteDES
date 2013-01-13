<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  = $cookie_read[0];
    $permission = $cookie_read[6];
    
    // Costruisco i menu 
    $mio_menu = gas_menu_completo($user);
    
    // scopro come si chiama
    $usr = fullname_from_id($id_user);
    // e poi scopro di che gas ? l'user
    $id_gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;     
}    

//echo "Perm $permission  Do = $do  e id new = $id_new_user $id_gas=".id_gas_user($id_new_user);
    
    if(_USER_PERMISSIONS & perm::puo_gestire_utenti){
         if($do=="act"){
            if(isset($id_new_user)){
                if(id_gas_user($id_new_user)==$id_gas){
                     $res = $db->sql_query("UPDATE maaking_users SET
                                             isactive = '1',
                                             code='0',
                                             profile=''
                                             WHERE
                                             userid = '$id_new_user'
                                             LIMIT 1;");
                     
                     $msg = 'Utente attivato;<br> Una mail è stata mandata per avvisarlo.';
                     $soggetto = "Avvenuta attivazione account ReteDes.it";
                     $messaggio = 'Ciao, '.fullname_from_id($id_new_user).'<br>
                                   con questa mail ti avvisiamo che il tuo account su <a href="http://www.retedes.it">www.retedes.it</a> è stato attivato.<br>
                                   Da ora puoi accedere al sito con la tua username e password, ed iniziare ad usarlo.<br>
                                   <br>
                                   Se hai bisogno di aiuto prova a consultare <a href="http://wiki.retedes.it">le istruzioni </a>,
                                   in ogni caso il tuo referente '._SITE_NAME.' sarà ben lieto di darti una mano.<br>
                                   <br>
                                   Buoni acquisti GAS !!<br>
                                   ';
                     usleep(5000);
                     tweet("#retedes EVVIVA, nel ".gas_nome(_USER_ID_GAS)." c'è un nuovo utente !!"); 
                     
                     usleep(5000);
                     $ris = manda_mail("ReteDes.it - NO_REPLY",_SITE_MAIL_REAL,fullname_from_id($id_new_user),email_from_id($id_new_user),$soggetto,"","ACT",0,$id_user,$messaggio);
                     log_me(0,$id_new_user,"USR","ACT","Attivato ".fullname_from_id($id_new_user),$ris,"");
                          
                                                                 
                }         
            }                          
         }
    }else{
       pussa_via();
       exit; 
    }
    
     
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Comunicazione al mio GAS";
      
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
      
      $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale    
      $retegas->java_scripts_header[]=java_tablesorter("user_table");
      $retegas->java_scripts_header[]=java_superfish();
          // orizzontale                         

      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  gas_render_user_activate($id_gas,"user_table");

      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);