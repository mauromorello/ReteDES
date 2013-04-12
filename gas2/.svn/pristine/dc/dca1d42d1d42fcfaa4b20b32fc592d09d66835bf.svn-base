<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");

include_once ("../ordini_renderer.php");
include_once ("../../cassa/cassa_renderer.php");



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
        $msg = "Non hai i permessi necessari per eseguire operazioni sulla cassa.";
        include "../index.php";
        
    }

    if(db_nr_q_2("id_ordine",$id_ordine,"tipo_movimento","2","retegas_cassa_utenti")>0){
        $msg = 'Esiste già almeno un movimento di pagamento fornitore riferito a questo ordine. Se si
        inserisce un altro movimento esso sarà sommato a quelli precedenti. Per rettificare la cifra
        usare la funzione apposita, nel menù "cassa".
              '; 
    }
    
    if($do=="pay"){
        
        $pwd = md5($pwd);
         $result = $db->sql_query("SELECT userid,password FROM maaking_users WHERE userid='$id_user' AND password='$pwd'");

         if($db->sql_numrows($result) == 0){
         
          $msg .= "La password non è stata riconosciuta";
          
          $e_pwd++;
               
         }
        
        
        //cerca di sistemare l'input
        $importo=floatval(trim(str_replace(array(",","?"),array(".",""),$importo)));
        
        if(!valuta_valida($importo)){
            $valuta++;
            $msg .="Importo non riconosciuto";
            $importo="";
        }
        if($importo==0){
           $valuta++;
           $msg .="Un pagamento non può essere nullo";
           $importo=""; 
        }
        sanitize($descrizione_movimento);
        sanitize($numero_documento);
        
        if($contabilizza=="si"){
            $cont = "si";
            $date_cont = "NOW()";
        }else{
            $cont = "no";
            $date_cont = "NULL";
        }
        
        $id_ditta = ditta_id_from_listino(listino_ordine_from_id_ordine($id_ordine));
        
        $err = $valuta + $e_pwd;
        
        if($err==0){
        
        //INSERT IN CASSA UTENTI
        $my_query="INSERT INTO retegas_cassa_utenti (   id_utente ,
                                                        id_gas,
                                                        importo ,
                                                        segno ,
                                                        tipo_movimento ,
                                                        descrizione_movimento ,
                                                        numero_documento ,
                                                        data_movimento ,
                                                        id_cassiere ,
                                                        registrato ,
                                                        data_registrato ,
                                                        contabilizzato ,
                                                        data_contabilizzato,
                                                        id_ditta,
                                                        id_ordine
                                                      )VALUES(
                                                      '0',
                                                      '"._USER_ID_GAS."',
                                                      '".$importo."',
                                                      '-',
                                                      '2',
                                                      '".$descrizione_movimento."',
                                                      '".$numero_documento."',
                                                      NOW(),
                                                      '"._USER_ID."',
                                                      'si',
                                                      NOW(),
                                                      '".$cont."',
                                                      '".$date_cont."',
                                                      '".$id_ditta."',
                                                      '".$id_ordine."'          
                                                      )";                                         
        
        //echo $my_query;
        ///exit;
        //INSERT BEGIN ---------------------------------------------------------
         $result = $db->sql_query($my_query);
         if (is_null($result)){
            $msg = "Errore nell'inserimento nella cassa";
        }else{
            $msg = "OK";
            $ok++;
        };
            
            
        if($ok==1){
                header("Location: ".$RG_addr["sommario"]."?q=10");
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
           $msg .="Un movimento non può essere nullo<br>";
            
        }
        if($valuta==0){        
           $h = cassa_pay_ditta_confirm($id_ute);
        }else{
           $h = cassa_pay_ditta($id_ut); 
        }
         
    }else{
           $h = cassa_pay_ditta($id_ut);
    }
         
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Aggiungi crediti a ".fullname_from_id($id_ut);
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma  vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = ordini_menu_pacco($id_ordine);
    $retegas->menu_sito[] = ordini_menu_visualizza($user,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_operazioni_base($id_user,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_mia_spesa($id_user,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_gas($id_user,$id_ordine,$id_gas);
    $retegas->menu_sito[] = ordine_menu_gestisci_new($id_user,$id_ordine,$id_gas);
    $retegas->menu_sito[] = ordine_menu_cassa($id_user,$id_ordine,$id_gas);
    $retegas->menu_sito[] = ordine_menu_comunica($id_user,$id_ordine,$id_gas);
    $retegas->menu_sito[] = ordine_menu_extra($id_ordine);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  
    
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion("",1); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

                 


       
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
     
      
      
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  schedina_ordine($id_ordine).$h;
      
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);    
  
?>