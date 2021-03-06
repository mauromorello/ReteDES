<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id_ordine;





// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;     
}    

//COntrollo permessi


if(!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
    go("sommario",_USER_ID,"Non puoi partecipare agli ordini");             
}
        
//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_creare_ordini)){
     go("sommario",_USER_ID,"Non puoi fare il referente per gli ordini.");
}        
 

//Se non esiste l'ordine    
if(ordine_inesistente($id_ordine)){
        go("sommario",_USER_ID,"Ordine inesistente");
}


// Se in quell'ordine non posso fare nulla
//if(!(_USER_PERMISSIONS AND perm::puo_gestire_retegas)){    
    if(ordine_io_cosa_sono($id_ordine,_USER_ID)==0){
            go("sommario",_USER_ID,"Questo ordine non ti compete");    
    }
//}


//controllo che l'utente non appartenga allo stesso gas
if(id_gas_user(id_referente_ordine_globale($id_ordine,_USER_ID_GAS))== _USER_ID_GAS){
    go("sommario",_USER_ID,"Sei dello stesso gas"); 
}


//Controllare se esiste gi? una referenza per quell'ordine


 if($do=="add_ref"){ 
      //aggiungi referenza
     if((int)$accetto_referenza==1){  

     
     
        $result=$db->sql_query("UPDATE retegas_referenze SET retegas_referenze.id_utente_referenze = '"._USER_ID."'
                            WHERE (((retegas_referenze.id_ordine_referenze)='$id_ordine') AND ((retegas_referenze.id_gas_referenze)='"._USER_ID_GAS."'));");
        
        //--------------------------------
          $da_chi = fullname_from_id(_USER_ID);
          $mail_da_chi = id_user_mail(_USER_ID);
        
          
        $descrizione_ordine = descrizione_ordine_from_id_ordine($id_ordine);
        
       $qry="SELECT
                maaking_users.fullname,
                maaking_users.email,
                maaking_users.user_site_option,
                retegas_referenze.id_gas_referenze,
                retegas_gas.descrizione_gas,
                maaking_users.userid
                FROM
                retegas_ordini
                Inner Join retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze
                Inner Join maaking_users ON retegas_referenze.id_gas_referenze = maaking_users.id_gas
                Inner Join retegas_gas ON retegas_referenze.id_gas_referenze = retegas_gas.id_gas
                WHERE
                retegas_ordini.id_ordini =  '$id_ordine'
                AND
                retegas_referenze.id_gas_referenze = '"._USER_ID_GAS."'";
        $result = $db->sql_query($qry);
        $lista_destinatari ="";
        while ($row = mysql_fetch_array($result)){
           // if($row[2] & opti::aggiornami_nuovi_ordini){ 
                $verso_chi[] = $row[0] ;
                $mail_verso_chi[] = $row[1] ;
                $lista_destinatari .= $row[0]." (".$row[4]."); <br>";
           //     }

        }// END WHILE

        $soggetto = "["._SITE_NAME."] - [HABEMUS REFERENTEM] $da_chi per ordine $id_ordine ($descrizione_ordine)";
        $msg_mail = "L'utente $da_chi si è offerto come REFERENTE GAS per questo ordine.<br>
                     Grazie al suo spirito d'iniziativa ora potrete acquistare merce tramite un'altro GAS.<br>
                     Egli si occuperà di recuperare il materiale una volta arrivato e per quanto riguarda la distribuzione ed il pagamento vi fornirà precise indicazioni<br>
                     Buoni acquisti.<br>";
        
        tweet(substr("Il ".gas_nome(_USER_ID_GAS)." parteciperà all'ordine ".substr($descrizione_ordine,0,50)."... gestito dal ".gas_nome(id_referente_ordine_globale($id_ordine)).".",0,140));
        sleep(1);
        manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"REF",$id_ordine,_USER_ID,$msg_mail);

        //--------------------------------
        
        $msg  = "Referenza aggiunta.<br>Ora tu ed il tuo GAS potrete ordinare articoli da questo ordine.<br>"; 
        $msg .= "Una mail per comunicare il lieto evento è stata inviata a : <br>$lista_destinatari";
        go("ordini_form",_USER_ID,$msg,"?id_ordine=$id_ordine");
        
        
     }else{
          $msg = "Per diventare Referente del tuo GAS devi prima accettare le condizioni di partecipazione, spuntando la casellina sopra il pulsante verde";
          unset($do);
     }
 }         
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Scheda aggiunta Referente GAS";
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    //$retegas->menu_sito[] = ordini_menu_pacco($id_ordine);
    $retegas->menu_sito[] = ordini_menu_visualizza($user,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_operazioni_base(_USER_ID,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_mia_spesa(_USER_ID,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_gas(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_gestisci_new(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_cassa(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_comunica(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_extra($id_ordine);     
    
    
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");
    
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null,3); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_top_body[] = '
                                                <script type="text/javascript">
                                                 $(document).ready(function(){
                                                    $("#blink_me").effect("pulsate", { times:5 }, 4000);
                                                })
                                                </script>
                                                ';


           
      // assegno l'eventuale messaggio da proporre

      if(!isset($msg)){    
      $msg = read_option_text($id_user,"MSG");
             delete_option_text($id_user,"MSG");
      }
      
      $retegas->messaggio=$msg;
      
      $h =  schedina_ordine($id_ordine);
      
 
//----------------nuovo form
$output_html .= "<div class=\"rg_widget rg_widget_helper\">
                    <form method=\"POST\" class=\"retegas_form\" action=\"\">"; 
$output_html .="<table>
   <tr >
      <td>
         Accetto di diventare referente di questo ordine per il MIO gas. Questo vuol dire che dovrà occuparmi di raccogliere gli ordini che mi perverranno dagli iscritti al mio GAS, e dovrà gestire il recupero della merce ed i pagamenti che verranno effettuati al gestore principale dell'ordine. Cliccando la casella qui sotto mi impegno a rispettare gli impegni presi.
      </td>
   </tr>   
<tr>    
      <td><center><strong>Accetto</strong> <input type=\"checkbox\" name=\"accetto_referenza\" value=\"1\">
      </td>
   

    <tr>
    </table>  
     

        <input type=\"hidden\" name=\"do\" value=\"add_ref\">
        <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
        <center>
        <input class =\"large green awesome\" style=\"margin:20px;\" type=\"submit\" value=\"SI, DIVENTERO' IL  REFERENTE !!\">
        </form>
        <br>
        
        <center>
        <a class =\"large red awesome\" style=\"margin:20px;\" href=\"".$RG_addr["sommario"]."\"><strong>No grazie, magari un altra volta.</strong></a>
        </div> 
  
";
      
      
      $retegas->content  =  $h.$output_html;
      
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);      
      
      //echo "<br>Scheda : ".(array_sum(explode(' ', microtime())) - $start);
       
?>    