<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("cassa_renderer.php");


if(isset($id_ut)){
    (int)$id_ut = mimmo_decode($id_ut);
}

// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
    $permission = $cookie_read[6];
                                        
    // e poi scopro di che gas ? l'user
    $id_gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;     
}    

    //COntrollo permessi
    if(!($permission & perm::puo_gestire_la_cassa)){
        unset($do);
        go("sommario",$id_user,_OP_NOT_PERMITTED);

        
    }

    if($do=="sub"){
        
         $time_now = time();
         $time_last_op = read_option_text($id_user,"ADD_CREDIT");
         if(($time_now - $time_last_op) < 10){
             go("cassa_gas_panel",NULL,NULL,"?alert=90");   
         }
        
           
         (int)$validation = mimmo_decode($validation);  
         $time_diff = $time_now - $validation;
         
         if($time_diff>60){
             go("sommario",$id_user,"Il tempo per effettuare questa operazione è scaduto, riparti daccapo.");
         }
        
         
         if(read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_USE_PASSWORD_CONFIRM")=="SI"){
                $pwd = md5($pwd);
                $result = $db->sql_query("SELECT userid,password FROM maaking_users WHERE userid='$id_user' AND password='$pwd'");
                if($db->sql_numrows($result) == 0){
                     $msg .= "La password non è stata riconosciuta";
                $e_pwd++;
               }
         }
        
        
         
        
        
        //cerca di sistemare l'input
        $importo=floatval(trim(str_replace(array(",","€"),array(".",""),$importo)));
        
        if(!valuta_valida($importo)){
            $valuta++;
            $msg .="Importo non riconosciuto";
            $importo="";
        }
        if($importo==0){
           $valuta++;
           $msg .="Un movimento non può essere nullo";
           $importo=""; 
        }
        sanitize($descrizione_movimento);
        
        if($contabilizza=="si"){
            $cont = "si";
            $date_cont = "NOW()";
        }else{
            $cont = "no";
            $date_cont = "NULL";
        }
        
        
        
        $err = $valuta + $e_pwd;
        
        if($err==0){
        
        //INSERT IN CASSA UTENTI
        $my_query="INSERT INTO retegas_cassa_utenti (   id_utente ,
                                                        id_gas,
                                                        importo ,
                                                        segno ,
                                                        tipo_movimento ,
                                                        descrizione_movimento ,
                                                        data_movimento ,
                                                        id_cassiere ,
                                                        registrato ,
                                                        data_registrato ,
                                                        contabilizzato ,
                                                        data_contabilizzato
                                                      )VALUES(
                                                      '".$id_ute."',
                                                      '".$id_gas."',
                                                      '".$importo."',
                                                      '-',
                                                      '4',
                                                      '".$descrizione_movimento."',
                                                      NOW(),
                                                      '".$id_user."',
                                                      'si',
                                                      NOW(),
                                                      '".$cont."',
                                                      ".$date_cont."          
                                                      )";                                         
        
        //echo $my_query;
        ///exit;
        //INSERT BEGIN ---------------------------------------------------------
        $result = $db->sql_query($my_query);
        if (is_null($result)){
            $msg = "Errore nell'inserimento nella cassa Utenti";
        }else{
            $msg = "OK";
            $ok++;
            write_option_text($id_user,"ADD_CREDIT",time());
        };
            
            
        if($ok==1){
                
                
                $da_chi = _USER_FULLNAME;
                $mail_da_chi = email_from_id(_USER_ID);
                
                $verso_chi = fullname_from_id($id_ute);
                $mail_verso_chi = email_from_id($id_ute);
                
                $soggetto ="["._SITE_NAME." - NOTA] Scarico credito dal tuo conto.";
                $cr = _nf(cassa_saldo_utente_totale($id_ute));
                
                $query_op = "SELECT id_cassa_utenti,
                                    data_movimento
                                    FROM retegas_cassa_utenti 
                                    WHERE
                                    id_utente = '$id_ute'
                                    AND tipo_movimento= '4'
                                    AND importo = '$importo'
                                    ORDER BY data_movimento DESC
                                    LIMIT 1;";
                $res_op = $db->sql_query($query_op);                    
                $row_op = $db->sql_fetchrow($res_op);
                $n_op = $row_op[0];
                $data_op =  conv_datetime_from_db($row_op[1]);
                $intestazione_gas = gas_estremi(_USER_ID_GAS);
                
                $messaggio_html = "<h2>NOTA DI SCARICO CREDITO</h2>
                <hr>
                $intestazione_gas
                <hr>
                <strong>$da_chi</strong>, cassiere del tuo gas,<br>
                in data odierna ($data_op) ha dovuto scaricare a $verso_chi dal proprio conto gas<br>
                <span style=\"font-size:1.3em\">$importo Euro.</span><br>
                <br>
                Il numero di documento è : $descrizione_movimento <br>
                Il numero dell'operazione è : $n_op <br>
                <br>
                Attualmente, il tuo credito totale disponibile è $cr Eu.<br>
                ma vi potrebbero essere dei movimenti che non sono stati ancora contabilizzati.<br>
                Verifica la tua situazione su <a href=\"http://www.retedes.it\">www.retedes.it</a>";
                
                manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,null,"CAS",0,_USER_ID,$messaggio_html);    
                go("sommario",$id_user,"Hai correttamente scaricato $importo Euri a ".fullname_from_id($id_ute));
        }else{
                go("sommario",$id_user,"E' successo qualcosa di imprevisto durante questa operazione");
            
            }
            
            
        }
        
    }
    
    if($do=="conf"){
          if(!valuta_valida($importo)){
            $valuta++;
            $msg .="Importo non riconosciuto<br>";
            
        }
        if($importo==0){
           $valuta++;
           $msg .="Un movimento non puè essere nullo<br>";
            
        }
        if($valuta==0){        
           $h = cassa_sub_credits_confirm($id_ute);
        }else{
           $h = cassa_sub_credits($id_ut); 
        }
         
    }else{
           $h = cassa_sub_credits($id_ut);
    }
         
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Sottrai crediti a ".fullname_from_id($id_ut);
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    //$retegas->menu_sito = ordini_menu_completo($user);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  
    
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion("",menu_lat::gas); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

                 


       
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
     
      
      
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  $h;
      
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);    
  
?>