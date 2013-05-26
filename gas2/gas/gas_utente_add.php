<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  = $cookie_read[0];
    $actual_fullname = fullname_from_id($id_user);
    $actual_mail = id_user_mail($id_user);
    $actual_gas = gas_nome(id_gas_user($id_user));
    
    // Costruisco i menu 
    //$mio_menu[] = gas_menu_nuovo_gas($user);
    $mio_menu[] = gas_menu_gestisci_utenti($user);

    $mio_menu[] = gas_menu_visualizza($user); 
    $mio_menu[] = gas_menu_comunica($user);
    
    // scopro come si chiama
    $usr = fullname_from_id($id_user);
    // e poi scopro di che gas ? l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;     
}    

    //Controllo di poter fare certe cose
    if(!(_USER_PERMISSIONS & perm::puo_gestire_utenti)){
        pussa_via();
        exit;    
    }
    
    if ($do=="add"){
        
        $msg= "";
        //CONTROLLO CAMPI VUOTI
        if(empty($gas_nu_username) | $gas_nu_username==""){
            $empty ++;
            $msg .= "Campo \"Username\" vuoto<br>";
        }
        if(empty($gas_nu_fullname) | $gas_nu_fullname==""){
            $empty ++;
            $msg .= "Campo \"Nome completo\" vuoto<br>";
        }
        if(empty($gas_nu_password1) | $gas_nu_password1==""){
            $empty ++;
            $msg .= "Campo \"Password\" vuoto<br>";
        }
        if(empty($gas_nu_password2) | $gas_nu_password2==""){
            $empty ++;
            $msg .= "Campo \"Riscrivi password\" vuoto<br>";
        }
        if(empty($gas_nu_tel) | $gas_nu_tel==""){
            $empty ++;
            $msg .= "Campo \"telefono\" vuoto<br>";
        }
        if(empty($gas_nu_mail) | $gas_nu_mail==""){
            $empty ++;
            $msg .= "Campo \"mail\" vuoto<br>";
        }
        
        //CONTROLLO PASSW1=PASSW2
        if($gas_nu_password1 != $gas_nu_password2){
            $logival ++;
            $msg .= "Le due password non coincidono<br>";
        }
        
        //CONTROLLO LUNGHEZZA USERNAME
        if(strlen($gas_nu_username)>15){
            $logical ++;
            $msg .= "lo username ($gas_nu_username) non può superare i 15 caratteri<br>";
        }
        
        
        //CONTROLLO FORMATO MAIL
        if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", trim($gas_nu_mail))){
                //print the error message and load the form.
                $msg .= "La Mail immessa ($gas_nu_mail) non è in formato valido<br>"; 
                $logical++;
          }
        
        //CONTROLLO MAIL O USERNAME DOPPI
          $sql_email_check = $db->sql_query("SELECT email FROM maaking_users WHERE email='$gas_nu_mail'");
          $sql_username_check = $db->sql_query("SELECT username FROM maaking_users WHERE username='$gas_nu_username'");
          $email_check = $db->sql_numrows($sql_email_check);
          $username_check = $db->sql_numrows($sql_username_check);

          if(($email_check > 0) || ($username_check > 0)){
               if($email_check > 0){
                  $msg .= "La Mail immessa esista già in RETEGAS.AP<br>";
                  $logical++;
                  unset($gas_nu_mail);
               }

               if($username_check > 0){
                  $msg .= "L'utente esiste già in ReteGas.AP";
                  $logical++;
                  unset($gas_nu_username);
               }
          }
        
        
        
        if(($empty + $logical)==0){
            //AGGIUNGI UTENTE
          $md5_password = md5($gas_nu_password1);
          $gas_nu_gasapp = id_gas_user($id_user); 
          $consenso=1;
          $permessi = leggi_permessi_default($gas_nu_gasapp);
          $result = $db->sql_query("INSERT INTO maaking_users 
                                        (username,
                                         password,
                                         email,
                                         fullname,
                                         regdate,
                                         isactive,
                                         code,
                                         id_gas,
                                         consenso,
                                         tel,
                                         user_permission,
                                         user_site_option)
                                         VALUES
                                        ('$gas_nu_username',
                                         '$md5_password',
                                         '$gas_nu_mail',
                                         '$gas_nu_fullname',
                                         NOW(),
                                         '1',
                                         '0',
                                         '$gas_nu_gasapp',
                                         '1',
                                         '$gas_nu_tel',
                                         '$permessi',
                                         '31');");
          if (!$result) {
            $msg .= "ERRORE Del DATABASE. Contattare "._SITE_MAIL_LOG;
          }
                
          //MANDA UNA MAIL CON IL CONSENSO.
        
        
        $soggetto = _SITE_NAME.": Sei stato iscritto al sito da $actual_fullname";
        $messaggio_html = 'Ciao '.$gas_nu_fullname.',<br>
                      Ricevi questa mail generata automaticamente, perchè '.$actual_fullname.', del '.$actual_gas.' ti ha appena iscritto a '._SITE_NAME.',<br>
                      Il sito che collega tutti i GAS e i DES<br>
                      <br>
                      '._SITE_NAME.' è raggiungibile da questo <a href="http://www.retedes.it">link</a>,<br>
                      e puoi accedere alla parte privata del sito usando come<br>
                      --------------------------------------------------------<br>
                      USERNAME : "'.$gas_nu_username.'"<br>
                      PASSWORD : "'.$gas_nu_password1.'"<br>
                      --------------------------------------------------------<br>
                      Fai attenzione alle lettere maiuscole e/o minuscole ed agli eventuali spazi.<br>
                      <br>
                      Per prima cosa, una volta entrato nel sito, cambia subito la tua password,<br>
                      in modo da essere l\'unico a conoscerla.<br>
                      Per sapere come fare consulta <a href="wiki.retegas.info">le istruzioni</a>, nelle quali vi sono anche specificate<br>
                      le regole di utilizzo di questo sito ed il suo disclaimer.<br>
                      <br>
                      
                      Accedendovi lo accetti incondizionatamente, <br>
                      prendendo atto del fatto che il tuo nominativo sarà usato esclusivamente per la gestione degli ordini,<br>
                      e la scheda con i tuoi dati sarà comunque disponibile anche alla visione da parte degli altri utenti iscritti.<br>    
                      <br>
                      <br>
                      Se questa mail vi fosse arrivata erroneamente, oppure ritenete di non poter accettare le regole di utilizzo o di condividere
                      i vostri dati personali,<br>
                      cancellatela pure, il vostro account si eliminerà da solo entro 48 ore.<br>
                      <br>
                      Grazie per l\'attenzione,<br>
                      '.$actual_fullname.',<br>
                      <b>'.$actual_gas.'</b>
                      ';
        $go = manda_mail($actual_fullname,$actual_mail,$gas_nu_fullname,$gas_nu_mail,$soggetto,null,null,0,_USER_ID,$messaggio_html);
        if ($go>0){
        
            $msg = "Utente inserito e mail di avviso inviata correttamente";
            go("gas_users",_USER_ID,$msg);
            exit;
        }    
            
            
        }else{
            
        }
        
        
        
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
      $retegas->java_scripts_header[]=java_superfish();
          // orizzontale                         

      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  gas_render_new_user_form($user);

      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);      
