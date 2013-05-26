<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("../function_engine/functionmsg.php");
include_once ("utenti_render.php");




    if ($do=="do_register"){
          
          
          
              
          //$username = sanitize($username);    
          if(is_empty($username)){
              $err_empty++;
              $msg .= "Manca il nome utente<br>";
          }
          
          //if(!isValid($username)){
          //   $err_log++;
          //   $msg .= "L'username scelto contiene caratteri non validi<br>"; 
          //}
          
          
          if(is_empty($password)){
              $err_empty++;
              $msg .= "Manca la prima password<br>";
          }
          //if(!isValid($password)){
          //   $err_log++;
          //   $msg .= "La password scelta contiene caratteri non validi<br>"; 
          //}
          
          
          if(is_empty($password2)){
              $err_empty++;
              $msg .= "Manca la seconda password<br>";
          }
          
          $email = sanitize($email);
          if(is_empty($email)){
              $err_empty++;
              $msg .= "Manca la tua email<br>";
          }
          
          $fullname = sanitize($fullname);
          if(is_empty($fullname)){
                $err_empty++;
                $msg .= "Manca il tuo nome completo<br>";
          }
          
          $tel = sanitize($tel);
          if(is_empty($tel)){
                $err_empty++;
                $msg .= "Manca il tuo recapito telefonico<br>";
          }
          
          
          if($password != $password2){
                $msg .= "Le due password non coincidono<br>"; 
                $err_log++;
          }
          if(strlen($username)>15){
                $msg .= "Il nome utente non puo' essere più lungo di 15 caratteri<br>"; 
                $err_log++;   
          }
          
          if($consenso <> "1"){
                $msg .= "Manca il tuo consenso ad accettare le regole del sito.<br>"; 
                $err_log++;
          }
          
          if($gasappartenenza == "-1"){
                $msg .= "Devi scegliere un gas al quale iscriverti<br>"; 
                $err_log++;
          }
          
          
          if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
                $msg .= "La mail inserita non è stata accettata<br>"; 
                $err_log++;
          }
          
          
          
          $sql_email_check = $db->sql_query("SELECT email FROM maaking_users WHERE email='$email'");
          $sql_username_check = $db->sql_query("SELECT username FROM maaking_users WHERE username='$username'");
          $email_check = $db->sql_numrows($sql_email_check);
          $username_check = $db->sql_numrows($sql_username_check);

          

          if($email_check > 0){
                  $msg .= "Hai inserito una mail che esiste già<br>"; 
                  $err_log++;
                  unset($email);
          }

          if($username_check > 0){
                  $msg .= "Hai inserito un nome utente che esiste già<br>"; 
                  $err_log++;
                  unset($username);
          }


          $err_tot = $err_empty + $err_log;
          
          if($err_tot==0){
          
            
          
          
            // USer non ancora attivato
            $isactive = 0;
          
            $code = md5(time());
            $code = rand(0,999999999);
            $subject = "["._SITE_NAME."] Nuovo account - Validazione";

            $md5_password = md5($password);
            $gasappartenenza = intval($gasappartenenza); 
            $consenso=intval($consenso);
            $permessi = leggi_permessi_default($gasappartenenza);
            
            
            $messaggio_referente_q = sanitize($messaggio_referente);
            $result = $db->sql_query("INSERT INTO maaking_users (username,password,email,fullname,regdate,isactive,code,id_gas,consenso,tel,user_permission,profile) "
                                                       ."  VALUES('$username','$md5_password','$email','$fullname',NOW(),'$isactive','$code','$gasappartenenza','$consenso','$tel','$permessi','$messaggio_referente_q');");
            if (!$result) {
                die('Errore interno: ' . mysql_error().";");
            }
          
          $message_u = message_mail_utente($username,$password,strip_tags($messaggio_referente));
          $message_a = message_mail_admin_gas($username,$fullname,$tel,$code,strip_tags($messaggio_referente),gas_nome($gasappartenenza)); 
          
          
          
          $email_a = id_gas_mail($gasappartenenza);
          $headers = message_headers($site_name,_SITE_MAIL_REAL);
          
          $go1 =  mail($email,$subject,$message_u, $headers);
          sleep(1);
          $go2 =  mail($email_a,$subject,$message_a, $headers);
          sleep(1);
          $go3 =  mail(_SITE_MAIL_LOG,"Nuova attivazione",$message_a, $headers);    
          
          if(!$go1 or!$go2){
             $msg.="Problema durante l'invio della mail";
          }else{
             c1_go_away("?q=registrazione_ok");
          }
          
          }    
        
        
    }

   
   // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito;
    $retegas->posizione = "Scheda registrazione";
    

     

    
    $retegas->sezioni = $retegas->html_standard;

    
    // Menu specifico per l'output  
    $mio_menu[]='<li><a class="medium yellow awesome" href="'.$RG_addr["sommario"].'?do=cosa_gas">Cosa sono i GAS</a></li>';
    $mio_menu[]='<li><a class="medium green awesome" href="'.$RG_addr["sommario"].'?do=chi_partecipa">Chi partecipa</a></li>';
    $mio_menu[]='<li><a class="medium red awesome" href="'.$RG_addr["sommario"].'?do=progetto_retegas">Progetto ReteGas.AP</a></li>';
    $mio_menu[]='<li><a class="medium beige awesome" href="'.$RG_addr["sommario"].'?do=progetto_des">Progetto D.E.S</a></li>';
    //$mio_menu[]='<li><a class="medium awesome" href="'.$RG_addr["sommario"].'?do=come_fare">Per iscriversi</a></li>';
    //$mio_menu[]='<li><a class="awesome medium" href="'.$RG_addr["pag_users"].'?q=Register" ><strong>Registrati a ReteGas.AP !!</strong></a></li>';

    
    $id_ditta=$id;
    //$retegas->menu_sito = ditte_menu_completo($user,$id_ditta,"YES","ditte_form.php");
    $retegas->menu_sito = $mio_menu;
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
   
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }

            // qui ci va la pagina vera e proria
       
      $retegas->content  =  utenti_render_register_form();
                             
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
      
?> 