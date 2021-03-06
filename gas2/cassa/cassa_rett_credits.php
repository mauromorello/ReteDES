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
        $msg = "Non hai i permessi necessari per eseguire operazioni sui crediti.";
        include "../index.php";
        
    }

    if($do=="rett"){
        
        $pwd = md5($pwd);
         $result = $db->sql_query("SELECT userid,password FROM maaking_users WHERE userid='$id_user' AND password='$pwd'");

         if($db->sql_numrows($result) == 0){
         
          $msg .= "La password non è stata riconosciuta";
          
          $e_pwd++;
               
         }
        
        
        //cerca di sistemare l'input
        $importo=floatval(trim(str_replace(array(",","?"),array(".",""),$importo)));
        sanitize($descrizione_movimento);
        sanitize($note_movimento);
        
        
        
        if(empty($descrizione_movimento) || $descrizione_movimento==""){
            $valuta++;
            $msg .="La motivazione di questa operazione non pu? essere lasciata vuota"; 
            
        }
        
        if(!valuta_valida($importo)){
            $valuta++;
            $msg .="Importo non riconosciuto";
            $importo="";
        }
        
        

        if($registra=="si"){
            $regi = "si";
            $date_regi = "NOW()";
        }else{
            $regi = "no";
            $date_regi = "NULL";
        }
        
        if($contabilizza=="si"){
            $cont = "si";
            $date_cont = "NOW()";
            $regi = "si";
            $date_regi = "NOW()";
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
                                                        contabilizzato ,
                                                        data_contabilizzato,
                                                        registrato,
                                                        data_registrato
                                                      )VALUES(
                                                      '".$id_ute."',
                                                      '".$id_gas."',
                                                      '".$importo."',
                                                      '".$segno_movimento."',
                                                      '3',
                                                      '".$descrizione_movimento."',
                                                      NOW(),
                                                      '".$id_user."',
                                                      '".$cont."',
                                                      ".$date_cont.",
                                                      '".$regi."',
                                                      ".$date_regi."          
                                                      )";                                         
        
        //echo $my_query;
        //INSERT BEGIN ---------------------------------------------------------
         $result = $db->sql_query($my_query);
         if (is_null($result)){
            $msg = "Errore nell'inserimento nella cassa Utenti";
        }else{
            $msg = "OK";
            $ok++;
        };
            
        
            
            if($ok==1){
                
                header("Location: ".$RG_addr["sommario"]."?q=9");

            }
            
            
        }
        
    }
    
    if($do=="conf"){
           $h = cassa_rett_credits_confirm($id_ute); 
    }else{
           $h = cassa_rett_credits($id_ut);
    }
         
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Rettifica crediti a ".fullname_from_id($id_ut);
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = gas_menu_gestisci_cassa($user);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg",
                                    "ckeditor");  
    
          
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
      