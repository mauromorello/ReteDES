<?php

//V3
function lista_ordini_aperti(){
    global $db;
    $my_query="SELECT retegas_ordini.id_ordini,
            retegas_ordini.descrizione_ordini,
            retegas_listini.descrizione_listini,
            retegas_ditte.descrizione_ditte,
            retegas_ordini.data_chiusura,
            retegas_gas.descrizione_gas,
            retegas_referenze.id_gas_referenze,
            maaking_users.userid,
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE (((retegas_ordini.data_chiusura)>NOW()) AND ((retegas_ordini.data_apertura)<NOW()) AND ((retegas_referenze.id_gas_referenze)="._USER_ID_GAS."))
            ORDER BY retegas_ordini.data_chiusura ASC ;";


        $result = $db->sql_query($my_query);
        $n_rows = $db->sql_numrows($result);

        $riga=0;


        $t="<div class=\"list-group\">";

        while ($row = $db->sql_fetchrow($result)){
        $riga++;

         //TEMPO ALLA CHIUSURA
         $inittime=time();
         $datexmas=strtotime($row["data_chiusura"]);
         $timediff = $datexmas - $inittime;

            $days=intval($timediff/86400);
            $remaining=$timediff%86400;


            $hours=intval($remaining/3600);
            $remaining=$remaining%3600;

            $mins=intval($remaining/60);
            $secs=$remaining%60;

            if($days>0){
                $dd="<b>$days</b> "._pl("giorn","o","i",$days)." e <b>$hours</b> "._pl("or","a","e",$hours).".";
            }else{
                if($hours>0){

                $dd="<span class=\"label label-danger\">CHIUDE tra $hours "._pl("or","a","e",$hours)." !</span>";

                }else{
                $dd="<span class=\"label label-danger\">CHIUDE tra $mins "._pl("minut","o","i",$mins)." !</span>";

                }
            }


         $referente_generale = id_referente_ordine_globale($row["id_ordini"]);
         $referente_gas = id_referente_ordine_proprio_gas($row["id_ordini"],_USER_ID_GAS);

         if($referente_generale<>$referente_gas){

             if($referente_gas>0){
                $gas="<small>".fullname_referente_ordine_proprio_gas($row["id_ordini"],_USER_ID_GAS)."</small><br>";
             }else{
                $gas="<span class=\"label label-primary\">GAS Esterno</span>";

             }
         }else{
             $gas_ext="";
             $gas="<small>".fullname_referente_ordine_proprio_gas($row["id_ordini"],_USER_ID_GAS)."</small><br>";
         }

         $back_color="";
         if($referente_gas>0){
             $pal = '<a title="Ordine partecipabile"><IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;"></a>';
             $vis="OK";
             $ref="";
         }else{
             if(check_option_order_blacklist(_USER_ID_GAS,$row["id_ordini"])==0){
                $pal = '<a title="Manca il referente per il tuo GAS"><IMG SRC="'.$RG_addr["img_pallino_marrone"].'" ALT="NON Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;"></a>';
                $ref ="<span class=\"label label-warning\">Referente ?</span>";
                $back_color="ordine_esterno_class";
                $vis="OK";
             }else{
                $vis="";
                $ref="";

             }
         }

        $mia_spesa = valore_arrivato_netto_ordine_user($row["id_ordini"],_USER_ID);
        if($mia_spesa>0){
            $spesa="<span class=\"label label-success totale_ordine_box\" data-id_ordine=\"".$row["id_ordini"]."\">$mia_spesa &euro;</span>";
        }else{
            $spesa="";
        }

        if($vis=="OK"){

            $t .="
            <a href=\"#\" class=\"list-group-item goto_ord $back_color\" data-id_ordine=\"".$row["id_ordini"]."\" style=\"min-height:80px;\">
            <img class=\"pull-left\" SRC = ".icona_ordine($row["id_ordini"])." style=\"max-width:48px;margin-right:10px;margin-bottom:5px;\">
            <span class=\"pull-right\">$dd</span>
            $gas
            <span class=\"lead hidden-xs\" style=\"margin-bottom:0;\">".$row["descrizione_ordini"]."</span>
            <span class=\"visible-xs\">".$row["descrizione_ordini"]."</span>
            $ref $spesa $gas_ext

            </a>";

        }
        }//end while
        $t .="</div>";


        if($n_rows==0){$t="Nessun ordine aperto per il tuo GAS";}

        return '<div class="panel panel-primary">
                              <div class="panel-heading">
                                <h4 class="panel-title" style="color:#222222"><b>Ordini Aperti </b><small>Clicca su ogni ordine per aprirne la sua scheda.</small></h4>
                              </div>'.$t.'</div>';


}
function icona_ordine($id_ordine){
    global $db;
    $id_categoria = tipologia_id_from_listino(listino_ordine_from_id_ordine($id_ordine));
    switch ($id_categoria){

    case 1;
    $h = "http://retegas.altervista.org/gas3/icone/alimentari_240.png";
    break;

    case 2;
    $h = "http://retegas.altervista.org/gas3/icone/cereali_240.png";
    break;

    case 3;
    $h = "http://retegas.altervista.org/gas3/icone/frutta_verdura_240.png";
    break;

    case 4;
    $h = "http://retegas.altervista.org/gas3/icone/carne_pesce_240.png";
    break;

    case 5;
    $h = "http://retegas.altervista.org/gas3/icone/alimentari_240.png";
    break;

    case 7;
    $h = "http://retegas.altervista.org/gas3/icone/dolciumi_240.png";
    break;

    case 8;
    $h = "http://retegas.altervista.org/gas3/icone/intimo_240.png";
    break;

    case 12;
    $h = "http://retegas.altervista.org/gas3/icone/vino_240.png";
    break;

    case 13;
    $h = "http://retegas.altervista.org/gas3/icone/cosmetici_240.png";
    break;

    case 17;
    $h = "http://retegas.altervista.org/gas3/icone/formaggio_240.png";
    break;

    default:
    $h = "http://retegas.altervista.org/gas3/img/RD_v3_160.png";
    break;
    }


    return $h;
}
function status_ordine($id_ordine){
  global $db;
  $mia_spesa = valore_arrivato_netto_ordine_user($id_ordine,_USER_ID);
  $miei_articoli = round(qta_ord_ordine_user($id_ordine,_USER_ID),2);
  $mie_note_ordine = read_option_note(_USER_ID,"ORD_NOTE_".$id_ordine);

  if($mie_note_ordine==""){
    $mie_note_ordine = "Clicca per scrivere una nota riferita a quest'ordine";
    $empty = "Clicca per scrivere una nota riferita a quest'ordine";
  }
  if($mia_spesa>0){

  $h ='<div class="jumbotron">
          <p><strong>IN ORDINE:</strong></p>
          <p><strong>'.$miei_articoli.'</strong> '._pl("articol","o","i",$miei_articoli).'</p>
          <p>per <strong>'._nf($mia_spesa).'</strong> Euro</p>
          <p><a href="#" id="note_personali" data-type="textarea" data-pk="'.$id_ordine.'" data-url="_pages/ACT.php?act=update_note_personali" data-title="Le note personali saranno visibili dal referente ordine">'.$mie_note_ordine.'</a></p>
        </div>';
  }else{
   $h ='<div class="jumbotron">
          <p>Non hai ancora comprato nulla.</p>
        </div>';

  }

  return $h;
}
// UPDATE ORDINI (CRON)
function update_ordini_chiusi(){
  global $db,$RG_addr;
  $loggone=null;

// seleziona gli ordini ancora aperti (2) con data chiusura gi? passata;
$query_msg = "SELECT * from retegas_ordini
             WHERE ((retegas_ordini.id_stato='2')
             AND (retegas_ordini.data_chiusura <= now()));";
$result_msg = $db->sql_query($query_msg);

// se ci sono righe da modificare allora
if($db->sql_numrows($result_msg)>0){
    $loggone .= "Ci sono righe da modificare<br>";
    while ($row = $db->sql_fetchrow($result_msg)){
        $n++;
        $ordine = $row["id_ordini"];
        $descrizione = $row["descrizione_ordini"];
        $messaggio = "Ordine $ordine chiuso automaticamente.";
        $valore_ordine_netto = valore_totale_ordine($ordine);
        $utenti_ordine = ordine_bacino_utenti_part($ordine);
        $articoli_ordinati = articoli_in_ordine($ordine);

        $htg =read_option_gas_text_new(id_gas_user(id_referente_ordine_globale($ordine)),"_HASHTAG_GAS");
        if($htg<>""){
                 $htg = "#".$htg." ";
        }
        $msg_twitter = $htg."Ord. \"".substr($descrizione,0,30)."...\" CHIUSO (".gas_nome(id_gas_user(id_referente_ordine_globale($ordine))).")";



        //ELIMINAZIONE DEGLI ORDINI CON LA PRENOTAZIONE ANCORA ATTIVA
        //SONO A LIVELLO DI ORDINE
        //CERCO TRA LE OPZIONI QUELLA "" RIFERITA AD ORDINE, RICAVO UNA LISTA DI USERS
        //PER OGNI USER : CANCELLO ARTICOLI
        $verbose.= "Eliminazione articoli con prenotazione attiva. Ordine $ordine<br>";
        $sql_prenotazione = "SELECT * FROM retegas_options WHERE id_ordine='$ordine' AND chiave='PRENOTAZIONE_ORDINI';";
        $res_prenotazione = $db->sql_query($sql_prenotazione);
        $num_prenotazioni = $db->sql_numrows($res_prenotazione);
        $verbose.= "Trovate $num_prenotazioni prenotazioni, usando $sql_prenotazione<br>";
        while ($row_p = $db->sql_fetchrow($res_prenotazione)){

            $verbose .= "Utente : ".$row_p["id_user"]." Con prenotazione attiva, Cancellati i suoi articoli<br>";
             do_delete_all_ordine_user($ordine,$row_p["id_user"]);
             //Diamogli respiro
             usleep(500);
             //Cancello la prenotazione
             delete_option_prenotazione_ordine($ordine,$row_p["id_user"]);
        }


        //FINE ELIMINAZIONE ORDINI CON LA PRENOTAZIONE ANCORA ATTIVA

        $verbose .= controlla_integrita_ordine_totale($ordine);
        usleep(5000);


        $loggone .= "Riga $n - Ordine $ordine - $descrizione<br>";

        log_me($ordine,0,"ORD","MOD",$messaggio,0,$verbose);
        tweet($msg_twitter);

        $loggone .= "Loggato -$messaggio- <br>";


        $verso_chi = fullname_referente_ordine_globale($ordine);
        $mail_verso_chi = mail_referente_ordine_globale($ordine);
        $id_verso_chi = id_referente_ordine_globale($ordine);

        $da_chi = _SITE_NAME." - Non rispondere";
        $mail_da_chi = _SITE_MAIL_REAL;
        $gas_name = gas_nome(id_gas_user($id_verso_chi));

        // manda la mail di chiusura al referente
        if($row["mail_level"]>0){
            $loggone .= "Mail level di ordine $ordine maggiore di 0 <br>";
            $eol ="\r\n";

            if($utenti_ordine<>0){}else{

            $m = "PS : Siccome non vi sono partecipanti, è possibile cancellare questo ordine da ReteDes seguendo questo <a href=\"".$RG_addr["delete_ordine"]."?id_ordine=$ordine\" target=\"_blank\">link.</a>";

            }

            $message = "L'ordine $ordine ($descrizione) gestito da $gas_name, si è appena chiuso automaticamente.<br>
                         Lo puoi vedere nella pagina ORDINI CHIUSI del sito.<br>
                         ------------------------------------------------------ <br>
                         Valore netto merce ordinata : $valore_ordine_netto Eu. <br>
                         Utenti partecipanti : $utenti_ordine;  <br>
                         ------------------------------------------------------ <br>
                         Di seguito una lista di operazioni che puoi fare ora che l'ordine è chiuso:<br>
                         <br>
                         1. <a href=\"".$RG_addr["edit_costi"]."?id_ordine=$ordine\">Cambiare</a> le spese di spedizione e di gestione<br>
                         2. <a href=\"".$RG_addr["partecipat_cronologia"]."?id_ordine=$ordine\">Controllare</a> la cronologia degli acquisti<br>
                         3. <a href=\"".$RG_addr["edit_spese_gas"]."?id_ordine=$ordine\">Modificare</a> le spese sostenute dal proprio GAS<br>
                         4. <a href=\"".$RG_addr["convalida_ordine"]."?id_ordine=$ordine\">Confermare</a> o <a href=\"".$RG_addr["rettifica_singoli_valori"]."&id_ordine=$ordine\">correggere</a> i quantitativi di articoli arrivati, o i loro prezzi.<br>
                         5. <a href=\"".$RG_addr["edit_distribuzione_gas"]."?id_ordine=$ordine\">Impostare</a> le date e gli orari e i luoghi di distribuzione merce; (Per ogni gas partecipante).<br>
                         6. <a href=\"".$RG_addr["edit_scadenze"]."?id_ordine=$ordine\">Riaprire</a> l'ordine posticipandone la scadenza.<br>
                         7. <a href=\"http://www.treccani.it/enciclopedia/ecc-o-etc_(La_grammatica_italiana)/\">ecc ecc ecc</a>
                         <br>
                         Buona Distribuzione !!!
                         <br>
                          $m<br>
                         <br>  ";

              $message .= "Questa è una mail generata automaticamente,  <br>
                           non rispondere a questo indirizzo. " ;

              $soggetto = "["._SITE_NAME." * $gas_name*] - Chiusura ordine $ordine ($descrizione)";
              $mail_mandata = "";
             if(read_option_text($id_verso_chi,"_USER_OPT_SEND_MAIL")<>"NO"){
                manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$message,"AUT",$ordine);
                $mail_mandata = "(User NO Mail)";
                usleep(5000);
             }
             $loggone .= "Mail $mail_mandata da $da_chi ($mail_da_chi) verso $verso_chi ($mail_verso_chi) <br>";


            } // mail_level

            // manda la mail di chiusura ai partecipanti


    } //while

    // poi esegue l'aggiornamento
    $loggone .= "Uscito da ciclo ordini<br>";
    $query = "UPDATE `retegas_ordini`
                SET  `id_stato` =  '3'
                WHERE  (`retegas_ordini`.`data_chiusura` <= now())
                AND (`retegas_ordini`.`id_stato` = '2');";

    $result = $db->sql_query($query);
    $righe_interessate = $db->sql_affectedrows($result);
    usleep(5000);
    $loggone .= "Eseguito aggiornamento: $righe_interessate righe interessate<br>";
    log_me(0,0,"CRO","---",$loggone,0,"");

}

return $loggone;

}
function update_ordini_aperti() {
  global $db,$RG_addr;

  $loggone=null;

// seleziona gli ordini ancora da aprire  (1) con data apertura già passata;
$query_msg = "SELECT * from retegas_ordini
             WHERE ((retegas_ordini.id_stato='1')
             AND (retegas_ordini.data_apertura <= now()));";
$result_msg = $db->sql_query($query_msg);

// se ci sono righe da modificare allora
if($db->sql_numrows($result_msg)>0){
    $loggone .= "Ci sono ordini futuri da rendere presenti\n";
    while ($row = $db->sql_fetchrow($result_msg)){
        $n++;
        $ordine = $row["id_ordini"];
        $descrizione = $row["descrizione_ordini"];
        $note = $row["note_ordini"];
        if($note<>""){
            $note ="<p>Il gestore ha aggiunto delle note : <p><br><p>$note</p>";
        }else{
            $note ="";
        }

        $data_chiusura =conv_date_from_db($row["data_chiusura"]);
        $messaggio = "Ordine $ordine aperto automaticamente.";
        $msg_twitter = "\"".substr($descrizione,0,50)."..\" APERTO dal ".gas_nome(id_gas_user(id_referente_ordine_globale($ordine)))."!";

        $loggone .= "Riga $n - Ordine $ordine - $descrizione\n";

        log_me($ordine,0,"ORD","APE",$messaggio,0,"");
        tweet($msg_twitter);

        $loggone .= "Loggato -$messaggio- \n";

        // MANDA LE MAIL Agli utenti interessati
        //---------------------------------------------------

         $titolo_form_mail="Manda un messaggio al bacino di potenziali utenti di questo ordine, che hanno accettato di ricevere aggiornamenti da parte del sito.";

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
                maaking_users.isactive ='1' AND
                retegas_ordini.id_ordini =  '$ordine'";

        unset($verso_chi);
        unset($mail_verso_chi);

        $result = mysql_query($qry);
        $lista_destinatari ="";
        //Crea la lista dei destinatari
        while ($row_m = mysql_fetch_array($result)){

            //se l'utente ? attivo
            if(user_status($row[5])==1){
                //Se l'utente non vuole mail
                if(read_option_text($row[5],"_USER_OPT_SEND_MAIL")<>"NO"){
                    $verso_chi[] = $row_m[0] ;
                    $mail_verso_chi[] = $row_m[1] ;
                    $lista_destinatari .= $row_m[0]." (".$row_m[4].");\n";
                }
            }
        }// END WHILE

        //----------------------------------------------------


        $da_chi = _SITE_NAME;
        $mail_da_chi = _SITE_MAIL_REAL;
        $message =  "L'ordine $ordine ($descrizione) è aperto,<br>
                     e lo sarà fino al $data_chiusura (salvo modifiche da parte del referente).<br>
                     Lo puoi vedere nella pagina ORDINI APERTI del sito.<br>
                     oppure cliccando questo <a href=\"".$RG_addr["ordini_form"]."?id_ordine=$ordine\">link</a><br>
                     <br>
                     <p>Novità : E' possibile (per i referenti di questo ordine) impostare le date e gli orari di distribuzione merce, che appariranno nel calendario della versione 3.</p>
                     <br>
                     $note
                     <br>
                     Buoni acquisti !!!
                     <br>
                     <br>
                     <br>  ";

          $message .= "Questa è una mail generata automaticamente.  <br>" ;

          $soggetto = "["._SITE_NAME."] - Apertura  ordine $ordine ($descrizione)";

        manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($message),"AUT",$ordine,0,$message);

         $loggone .= "AVVISO DI APERTURA Mail da $da_chi ($mail_da_chi) verso:\n
                      $lista_destinatari \n";


    } //while

    // poi esegue l'aggiornamento
    $loggone .= "Uscito da ciclo ordini\n";
    $query = "UPDATE  `retegas_ordini`
    SET  `id_stato` =  '2'
    WHERE  `retegas_ordini`.`data_apertura` <= now()
    AND `retegas_ordini`.`data_chiusura` > now()
    ";

    $result = $db->sql_query($query);
    $righe_interessate = $db->sql_affectedrows($result);

    $loggone .= "Eseguito aggiornamento: $righe_interessate righe interessate\n";
    log_me(0,0,"CRO","---",$loggone,0,"");

}

return $loggone;

}

//FUNZIONI GENERICHE
function ordini_field_value($id_ordine,$field_name){
    Global $db;
    Global $debug;
    (int)$id_ordine;
    if(!(isset($id_ordine)|isset($field_name))){
        return null;
        exit;
    }
    $query = "SELECT * FROM retegas_ordini WHERE id_ordini='$id_ordine' LIMIT 1;";
    $res = $db->sql_query($query,null,"Ordini field value");
    $row = $db->sql_fetchrow($res);
    return $row["$field_name"];

}
function rompi_le_balle($ordine,$id_user=0,$nome_partecipante=null,$id_partecipante=null){

        global $RG_addr;

        $verso_chi = fullname_referente_ordine_globale($ordine);
        $mail_verso_chi = mail_referente_ordine_globale($ordine);
        $descrizione = descrizione_ordine_from_id_ordine($ordine);
        $valore_ordine_netto = valore_totale_ordine($ordine);
        $utenti_ordine = ordine_bacino_utenti_part($ordine);
        $articoli_ordinati = articoli_in_ordine($ordine);
        $nome_partecipante = fullname_from_id($id_user);
        $gas_partecipante = gas_nome(id_gas_user($id_user));

        $da_chi = _SITE_NAME;
        $mail_da_chi = "retegas@altervista.org";


        if(livello_rompimento_ordine($ordine)>1){

        $eol ="<br>";

        $message = "<a href=\"".$RG_addr["ordini_form_new"]."?id_ordine=$ordine\">Ordine - $ordine ($descrizione)</a>.$eol
                    Il Sig. $nome_partecipante del $gas_partecipante $eol
                    ha appena partecipato.
                    $eol
                    Adesso l'ordine vale  $valore_ordine_netto Eu.$eol
                    e vi stanno partecipando $utenti_ordine utenti;$eol
                    ------------------------------------------------------ $eol
                    $eol
                    ATTENZIONE l'ordine NON e' ancora chiuso, queste informazioni$eol
                    sono da considerarsi incomplete.$eol  ";
          $message .= "-------------------------------------------- $eol";
          $message .= _SITE_NAME."$eol";
          $message .= "$eol";
          $message .= "$eol";
          $message .= "Questa mail viene generata automaticamente.  $eol" ;


          $soggetto = "["._SITE_NAME."] - Rapporto attivita' su ordine $ordine ($descrizione)";

          //echo $message;

         manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,null,"AUT",$ordine,$id_user,$message);


        } // mail_level

}
function controlla_integrita_ordine_qord($id_ordine,$id_utente){


    global $db,$class_debug;
    $msg.= "Controllo integrità referenziale Q_Ord Ordine $id_ordine, user $id_utente";


    $sql_1 = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_utenti='$id_utente'";
    $res_1 = $db->sql_query($sql_1);

    While ($row = mysql_fetch_array($res_1)){

        $sql_2 = "SELECT
                    Sum(retegas_distribuzione_spesa.qta_ord) AS somma_qord
                    FROM
                    retegas_distribuzione_spesa
                    WHERE
                    retegas_distribuzione_spesa.id_articoli =  '".$row["id_articoli"]."' AND
                    retegas_distribuzione_spesa.id_user =  '".$id_utente."' AND
                    retegas_distribuzione_spesa.id_ordine =  '".$id_ordine."';";
        $res_2 =  $db->sql_query($sql_2);
        $row_2 = $db->sql_fetchrow($res_2);
        $somma_distribuzione_qord = round($row_2[0],4);

       $msg.= " Art ".$row["id_articoli"]." - DETTAGLIO = ".round($row["qta_ord"],4)." - SOMMA distribuzione - $somma_distribuzione_qord<br>";


            if($somma_distribuzione_qord<>round($row["qta_ord"],4)){
                $err++;
            }

        }

        if($err>0){
            //log_me($id_ordine,_USER_ID,"ERR","QOR","ERRORE IN Q ORD",0,$msg);
            return false;
            break;
        }

        return true;


}
function controlla_integrita_ordine_qarr($id_ordine,$id_utente){
    l("Controllo integrità referenziale Q_Arr Ordine $id_ordine, user $id_utente");
    global $db;
    $sql_1 = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_utenti='$id_utente'";
    $res_1 = $db->sql_query($sql_1);

    While ($row = mysql_fetch_array($res_1)){

        $sql_2 = "SELECT
                    Sum(retegas_distribuzione_spesa.qta_arr) AS somma_qarr
                    FROM
                    retegas_distribuzione_spesa
                    WHERE
                    retegas_distribuzione_spesa.id_articoli =  '".$row["id_articoli"]."' AND
                    retegas_distribuzione_spesa.id_user =  '".$id_utente."' AND
                    retegas_distribuzione_spesa.id_ordine =  '".$id_ordine."';";
        $res_2 =  $db->sql_query($sql_2);
        $row_2 = $db->sql_fetchrow($res_2);
        $somma_distribuzione_qarr = round($row_2[0],4);

        l(" Art ".$row["id_articoli"]." - DETTAGLIO = ".round($row["qta_ord"],4)." - SOMMA distribuzione - $somma_distribuzione_qarr");



            if($somma_distribuzione_qarr<>round($row["qta_arr"],4)){
                $err++;
            }

        }

        if($err>0){
            return false;
            break;
        }

        return true;

}

function controlla_integrita_ordine_totale($id_ordine){
    global $db;

    $sql ="SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine';";
    $h .=  "<div>";
    $h .=  "<h4>Controllo integrità ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).")</h4>";
    $h .=  $sql."<p>";
    $res = $db->sql_query($sql);
    While ($row = mysql_fetch_array($res)){
        $h .= "UTENTE : ".$row["id_utenti"]." - (".fullname_from_id($row["id_utenti"]).") ARTICOLO: ".$row["id_articoli"]."
        <br>DETTAGLIO ".$row["id_dettaglio_ordini"].", Q_ord: ".$row["qta_ord"]." Q_arr: ".$row["qta_arr"]."<br>";


        $sql_d="SELECT
            sum(retegas_distribuzione_spesa.qta_ord) as Qord,
            sum(retegas_distribuzione_spesa.qta_arr) as Qarr,
            count(retegas_distribuzione_spesa.id_distribuzione) as Nrec
            FROM
            retegas_distribuzione_spesa
            WHERE
            retegas_distribuzione_spesa.id_riga_dettaglio_ordine = '".$row["id_dettaglio_ordini"]."'
            GROUP BY
            retegas_distribuzione_spesa.id_articoli
            LIMIT 1";
            $ret_d = mysql_query($sql_d);
            $row_d = mysql_fetch_row($ret_d);

            $q_ord = $row_d[0];
            $q_arr = $row_d[1];
            $n_rec = $row_d[2];


        if($q_ord<>$row["qta_ord"]){
            $h.="<strong>ERRORE Q ORD</strong><br>";
        }

        if($q_arr<>$row["qta_arr"]){
            $h.="<strong>ERRORE Q ARR</strong><br>";
        }

        $h .= "DISTRIBUZI ".$row["id_dettaglio_ordini"].", Q_ord: $q_ord  Q_arr: $q_arr, N. Records = $n_rec<br>";
        $h .= "<hr>";

    }
    $h .=  "</p>";
    $h .=  "</div>";
    return $h;
}

    //SCHEDE ORDINI
    function schedina_ordine($id_ordine){
    global $db,$RG_addr;




    if(isset($id_ordine)){
    $query = "SELECT * FROM retegas_ordini WHERE id_ordini='$id_ordine' LIMIT 1";
    $res = $db->sql_query($query);
    $row = $db->sql_fetchrow($res);

    if($row["id_stato"]==1){
        $stato = "PROGRAMMATO";
        $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Futuro" style="height:16px; width:16px;vertical_align:middle;border=0;" ></a>';
    }
    if($row["id_stato"]==2){
        $stato = "APERTO";
        if(id_referente_ordine_proprio_gas($row["id_ordini"],_USER_ID_GAS)>0){
            $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Partecipabile" style="height:16px; width:16px;border=0;" ></a>';
        }else{
            $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_marrone"].'" ALT="NON Partecipabile" style="height:16px; width:16px;vertical_align:middle;border=0;"></a>';
        }
    }
    if($row["id_stato"]==3){
        if(is_printable_from_id_ord($row["id_ordini"])){
            $stato = "CHIUSO - CONVALIDATO";
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="Stampabile" style="height:16px; width:16px;vertical_align:middle;border=0;">';

        }else{
            $stato = "CHIUSO - IN ATTESA DI CONVALIDA";
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="NON Stampabile" style="height:16px; width:16px;vertical_align:middle;border=0;">';

        }
    }

    $id_listino = $row["id_listini"];
    $id_ditta = ditta_id_from_listino($id_listino);


    $h = '<table>
        <thead>
        </thead>
        <tbody>
            <tr>
                <td width="33%">
                    <table class="nested">
                    <thead>
                    </thead>
                    <tbody>
                       <tr class="odd sinistra">
                            <th>Categoria</th>
                            <td>'.tipologia_nome_from_listino($row["id_listini"]).'</td>
                        </tr>
                        <tr class="odd sinistra">
                            <th>Stato:</th>
                            <td>'.$stato.'</td>
                        </tr>
                    </tbody>
                    </table>
                </td>

                <td width="33%">
                    <table class="nested">
                    <thead>
                    </thead>
                    <tbody>
                        <tr class="odd sinistra">
                            <th>Ditta</th>
                            <td><a href="'.$RG_addr["form_ditta"].'?id_ditta='.$id_ditta.'">'.ditta_nome(ditta_id_from_listino($row["id_listini"])).'</a></td>
                        </tr>
                        <tr class="odd sinistra">
                            <th>Listino</th>
                            <td><a href="'.$RG_addr["listini_scheda"].'?id_listino='.$id_listino.'">'.listino_nome($row["id_listini"]).'</a></td>
                        </tr>

                    </tbody>
                    </table>
                </td>

                <td width="33%">
                    <table class="nested">
                    <thead>
                    </thead>
                    <tbody >
                        <tr class="odd sinistra">
                            <th>Aperto il</th>
                            <td>'.conv_datetime_from_db($row["data_apertura"]).'</td>
                        </tr>
                        <tr class="odd sinistra">
                            <th>Chiude il</th>
                            <td>'.conv_datetime_from_db($row["data_chiusura"]).'</td>
                        </tr>
                    </tbody>
                    </table>
                </td>
            </tr>


        </tbody>
        </table>
        ';



    }

    if(read_option_prenotazione_ordine($id_ordine,_USER_ID)=="SI"){
        $prenota = " <span style=\"color:red;font-weight:bold\">(ORDINE in PRENOTAZIONE)</span> ";
    }else{
        $prenota = "";
    }


    $h2 = rg_toggable($pal.' Ordine n.'.$row["id_ordini"].' - '.$row["descrizione_ordini"].', di '.fullname_from_id($row["id_utente"])." ".$prenota,"schedina_ordine",$h,false);
    if($row["note_ordini"]<>""){
        $h2 .= rg_toggable("Note Ordine","note_ordine",$row["note_ordini"],true);
    }





    return $h2;

    }
    function schedona_ordine($id_ordine,$id_user=null){
    global $db;
    global $RG_addr;

    $euro = "&#8364";

    $id_gas= id_gas_user($id_user);
    $io_chi_sono = fullname_from_id($id_user);
    $gas_name = gas_nome($id_gas);
    $gas_ordine_id = id_gas_user(id_referente_ordine_globale($id_ordine));
    $gas_name_generale = gas_nome($gas_ordine_id);

    //ANAGRAFICHE
    $ordine_nome    =   descrizione_ordine_from_id_ordine($id_ordine);
    $id_listino     =   listino_ordine_from_id_ordine($id_ordine);
    $listino        =   listino_nome($id_listino);
    $id_ditta       =   ditta_id_from_listino($id_listino);
    $ditta          =   ditta_nome_from_listino($id_listino);
    $mail_ditta     =   ditta_mail_from_listino($id_listino);
    $tipologia      =   tipologia_nome_from_listino($id_listino);
    $data_apertura  =   conv_datetime_from_db(ordini_field_value($id_ordine,"data_apertura"));
    $data_chiusura  =   conv_datetime_from_db(ordini_field_value($id_ordine,"data_chiusura"));
    $data_merce  =   conv_datetime_from_db(ordini_field_value($id_ordine,"data_merce"));
    if($data_merce=="00/00/0000 00:00"){$data_merce="Non definita";}


    $note_ordine    =   ordini_field_value($id_ordine,"note_ordini");



    //OPINIONI

    $conteggio_opinioni = conteggio_opinione_ordine($id_ordine);

    if($conteggio_opinioni>0){

        $media_opinioni = _nf(media_opinione_ordine($id_ordine));
        $sezione_opinioni = "<tr class=\"scheda\">
                                        <th $col_1>Valutazione utenti</th>
                                        <td $col_2>$conteggio_opinioni opinioni, media <strong>$media_opinioni</strong> / 5,00</td>
                                    </tr>";
    }else{
        $sezione_opinioni = "";
    }

    //ARTICOLI
    $articoli_ord           =   n_articoli_ordinati_da_id_ordine($id_ordine);
    $articoli_arr           =   n_articoli_arrivati_da_id_ordine($id_ordine);
    $scatole_intere_arr     =   q_scatole_intere_ordine_arr($id_ordine);
    $scatole_intere_ord     =   q_scatole_intere_ordine($id_ordine);
    $avanzo_articoli_ord    =   q_articoli_avanzo_ordine($id_ordine);
    $avanzo_articoli_arr    =   q_articoli_avanzo_ordine_arr($id_ordine);

    // RUOLO
    $user_level = "Utente Semplice;<br> ";
        if (id_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user))==$id_user){
                $user_level .= "Referente Proprio GAS;<br> ";
        }
        if (id_referente_ordine_globale($id_ordine)==$id_user){
                $user_level .= "Referente ORDINE; ";
        }
    $id_referente_ordine = id_referente_ordine_globale($id_ordine);
    $id_referente_proprio_gas = id_referente_ordine_proprio_gas($id_ordine,$id_gas);
    $referente_generale = fullname_from_id($id_referente_ordine)." (".telefono_from_id($id_referente_ordine).")";
    $referente_gas = fullname_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user))." (".tel_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user)).")";

    if(check_option_aiuto_ordine($id_ordine,_USER_ID)==0){
        $offerta_aiuto="<a class=\"awesome small green\" href=\"".$RG_addr["aiutanti_offerta_form"]."?id_ordine=$id_ordine\">Offri il tuo aiuto !!</a>";

    }else{
        $offerta_aiuto="";
    }

    $lista_gestori_extra = crea_minilista_referente_extra($id_ordine);


    //IL REFERENTE NON PUO' AIUTARE SE STESSO
    if($id_user==$id_referente_ordine){
        $offerta_aiuto="";
    }

    $aiutanti = crea_lista_aiuti_ordine_attivi($id_ordine);

    //ORDINE IN PRENOTAZIONE
    if(read_option_prenotazione_ordine($id_ordine,_USER_ID)=="SI"){
        $prenota = " <span style=\"color:red;font-weight:bold\">(ORDINE in PRENOTAZIONE)</span> ";
    }else{
        $prenota = "";
    }


    //PRENOTAZIONE e VALORE ORDINE
    $valore_totale_ordine = _nf(valore_totale_ordine_qarr($id_ordine));
    $parte_prenotata = _nf(ordine_valore_parte_prenotata($id_ordine));
    $parte_confermata = _nf(ordine_valore_parte_confermata($id_ordine));




    // GOOGLE CALENDAR
    $google_cal ='<a href="http://www.google.com/calendar/event?action=TEMPLATE&text;='.$ordine_nome.'&dates;=20060415T180000Z/20060415T190000Z&location;=&details;=Try our Saturday brunch special:<br><br>French toast with fresh fruit<br><br>Yum!&trp;=true&sprop;=website:http://www.javacafebrunches.com&sprop;=name:Jave Cafe"><img src="//www.google.com/calendar/images/ext/gc_button1.gif"></a>';

    //STATO
    $stato_attuale = stato_from_id_ord($id_ordine);
    if($stato_attuale==1){
        $stato = "Programmato";
    }
    if($stato_attuale==2){
        if(ordini_field_value($id_ordine,"solo_cassati")=="SI"){
           $stato = "Aperto a chi ha la cassa.";
        }else{
           $stato = "Aperto a tutti.";
        }


    }
    if($stato_attuale==3){
        $stato = "Chiuso - ";
        if(is_printable_from_id_ord($id_ordine)){
            $stato .="<b>STAMPABILE</b>";
        }else{
            $stato .="<b>DA CONFERMARE</b>";
        }
    }

    //BACINO UTENZE
    $bacino_tot = ordine_bacino_utenti($id_ordine);
    $bacino_part = ordine_bacino_utenti_part($id_ordine);
    $bacino_non_part = $bacino_tot-$bacino_part;
    $bacino_percentuale = _nf((($bacino_part/$bacino_tot)*100))."%";

    $bacino_tot_mio_gas = gas_n_user($id_gas);
    $bacino_part_mio_gas = ordine_bacino_utenti_part_gas($id_ordine,$id_gas);
    $bacino_non_part_mio_gas = $bacino_tot_mio_gas-$bacino_part_mio_gas;
    $bacino_percentuale_mio_gas = number_format((($bacino_part_mio_gas/$bacino_tot_mio_gas)*100),1,",","")."%";

    $gas_coinvolti=ordine_gas_coinvolti($id_ordine);

    //SPESA ATTUALE
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $costo_globale_trasporto = valore_trasporto($id_ordine,100);
    $costo_globale_gestione = valore_gestione($id_ordine,100);
    $maggiorazione_percentuale_mio_gas = valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas);
    $costo_globale_mio_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
    if($maggiorazione_percentuale_mio_gas>0){
        $motivazione_maggiorazione = "(".testo_maggiorazione_mio_gas($id_ordine,$id_gas).")";
    }

    if($valore_globale_attuale_netto_qarr>0){
        $valore_personale_attuale_netto_qarr = valore_totale_mio_ordine($id_ordine,$id_user);
        $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
        if($valore_gas_attuale_netto_qarr>0){
            $percentuale_mio_ordine_gas = ($valore_personale_attuale_netto_qarr / $valore_gas_attuale_netto_qarr) *100;
        }else{
            $percentuale_mio_ordine_gas = 0;
        }
        $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
        $costo_trasporto =  ($costo_globale_trasporto / 100) * $percentuale_mio_ordine;
        $costo_gestione =  ($costo_globale_gestione / 100) * $percentuale_mio_ordine;
        $costo_personale_mio_gas = ($costo_globale_mio_gas /100)*$percentuale_mio_ordine_gas;
        $valore_maggiorazione_mio_gas = ($valore_personale_attuale_netto_qarr / 100) * $maggiorazione_percentuale_mio_gas;




        $totale_ordine =  $valore_personale_attuale_netto_qarr +
                          $costo_trasporto +
                          $costo_gestione +
                          $costo_personale_mio_gas +
                          $valore_maggiorazione_mio_gas ;




    }else{
        $valore_personale_attuale_netto_qarr = 0;
        $costo_trasporto=   0;
        $costo_gestione=    0;
        $costo_personale_mio_gas =0;
        $valore_maggiorazione_mio_gas =0;
    }

        $totale_ordine =    $valore_personale_attuale_netto_qarr +
                            $costo_trasporto +
                            $costo_gestione +
                            $costo_personale_mio_gas +
                            $valore_maggiorazione_mio_gas ;

       //FORMATTAZIONE
       $valore_personale_attuale_netto_qarr = number_format((float)round($valore_personale_attuale_netto_qarr,2),2,",","");
       $costo_trasporto = number_format((float)round($costo_trasporto,2),2,",","");
       $costo_gestione  = number_format((float)round($costo_gestione,2),2,",","");
       $costo_personale_mio_gas = number_format((float)round($costo_personale_mio_gas,2),2,",","");
       $valore_maggiorazione_mio_gas = number_format((float)round($valore_maggiorazione_mio_gas,2),2,",","");
       $totale_ordine = number_format((float)round($totale_ordine,2),2,",","");
       $maggiorazione_percentuale_mio_gas = number_format((float)round($maggiorazione_percentuale_mio_gas,2),2,",","");
       $costo_globale_mio_gas = number_format((float)round($costo_globale_mio_gas,2),2,",","");
       $costo_globale_trasporto = number_format((float)round($costo_globale_trasporto,2),2,",","");
       $costo_globale_gestione = number_format((float)round($costo_globale_gestione,2),2,",","");
       $articoli_ord = (float)round($articoli_ord,2);
       $articoli_arr = (float)round($articoli_arr,2);


       //GEOCODING

       //Ditta
       $ditta_gc_lat = db_val_q("id_ditte",$id_ditta,"ditte_gc_lat","retegas_ditte");
       $ditta_gc_lng = db_val_q("id_ditte",$id_ditta,"ditte_gc_lng","retegas_ditte");
       //echo "$ditta_gc_lat , $ditta_gc_lng<br>";
       if($ditta_gc_lat==0){
           $gc .= "Indirizzo ditta non valido<br>";
           $e_gc++;
       }

       //Mio Gas
       $gas_gc_lat = db_val_q("id_gas",$id_gas,"gas_gc_lat","retegas_gas");
       $gas_gc_lng = db_val_q("id_gas",$id_gas,"gas_gc_lng","retegas_gas");
       //echo "$gas_gc_lat , $gas_gc_lng<br>";
       if($gas_gc_lat==0){
           $gc .= "Indirizzo mio gas non valido<br>";
           $e_gc++;
       }

       //Gas ordinante
       if($gas_ordine_id<>$id_gas){
           $gas_ord_gc_lat = db_val_q("id_gas",$gas_ordine_id,"gas_gc_lat","retegas_gas");
           $gas_ord_gc_lng = db_val_q("id_gas",$gas_ordine_id,"gas_gc_lng","retegas_gas");
           //echo "$gas_gc_lat , $gas_gc_lng<br>";
           if($gas_ord_gc_lat==0){
               $gc .= "Indirizzo gas ordinante non valido<br>";
               $e_gc++;
           }
       }

       //user
       $user_gc_lat = db_val_q("userid",_USER_ID,"user_gc_lat","maaking_users");
       $user_gc_lng = db_val_q("userid",_USER_ID,"user_gc_lng","maaking_users");
       if($user_gc_lat==0){
           $gc .= "Indirizzo Utente non valido<br>";
           $e_gc++;
       }

       if($e_gc==0){
             //CHIAMA GOOGLE MAPS E SI FA PASSARE LA DISTANZA.
            $sResponse=curl_request('http://maps.googleapis.com/maps/api/distancematrix/json',
                "origins=$ditta_gc_lat,$ditta_gc_lng&destinations=$gas_gc_lat,$gas_gc_lng&mode=driving&sensor=false");
            $oJSON=json_decode($sResponse);

            if ($oJSON->status=='OK')

                    $dist_ditta_gas=(float)preg_replace('/[^\d\.]/','',$oJSON->rows[0]->elements[0]->distance->text);
            else
                    $dist_ditta_gas=0;

            $dist_ditta_gas = round(floatval($dist_ditta_gas),2);



            $dist_gas_user = round(getDistanceBetweenPointsNew($user_gc_lat, $user_gc_lng, $gas_gc_lat, $gas_gc_lng),2);
            if($gas_ord_gc_lat>0){
                $dist_gas_ord =  round(getDistanceBetweenPointsNew($gas_gc_lat, $gas_gc_lng, $gas_ord_gc_lat, $gas_ord_gc_lng),2);
                $dist_gas_ord = $dist_gas_ord." Km + ";
            }
            $dist_tot = round($dist_ditta_gas + $dist_gas_user + $dist_gas_ord,2);
            $gc = $dist_ditta_gas." Km + ". $dist_gas_ord.$dist_gas_user." Km = <strong>".$dist_tot."</strong> Km Tot.";

       }

     //MIE NOTE ORDINE

     if(valore_arrivato_netto_ordine_user($id_ordine,_USER_ID)>0){
         $classe_editable = "edit";
         $mie_note_ordine = read_option_note(_USER_ID,"ORD_NOTE_".$id_ordine);
         if($mie_note_ordine==""){
             $mie_note_ordine = "Clicca per scrivere";
         }
         $j_editable = "<tr class=\"titolino\">
                                        <td colspan=2 title=\"Le note personali potranno essere lette anche dal gestore dell'ordine e dal proprio referente GAS.<br>
                                                              Per inserire una nota Cliccare sul Post-it. Le note vengono salvate assieme al proprio ordine.\">
                                        NOTE PERSONALI
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                        <div id=\"postit\"
                                        class=\"edit_area\"
                                        style=\"display:block;
                                                height : 10em;
                                                width : 10em;
                                                background:#fefabc;
                                                padding:15px;
                                                font-family: 'Gloria Hallelujah', cursive;
                                                font-size:15px;
                                                color: #000;
                                                width:200px;

                                                -moz-transform: rotate(4deg);
                                                -webkit-transform: rotate(4deg);
                                                -o-transform: rotate(4deg);
                                                -ms-transform: rotate(4deg);
                                                transform: rotate(4deg);

                                                box-shadow: 0px 4px 6px #333;
                                                -moz-box-shadow: 0px 4px 6px #333;
                                                -webkit-box-shadow: 0px 4px 6px #333;
                                                \">$mie_note_ordine</div>
                                                </td>
                                    </tr>";

     }

     //Distribuzione
     $luogo_distribuzione = luogo_distribuzione_mio_gas($id_ordine,$id_gas);
     if($luogo_distribuzione==""){$luogo_distribuzione="Non definito...";}
     $data_distribuzione_start = conv_datetime_from_db(data_distribuzione_start_mio_gas($id_ordine,$id_gas));
     if($data_distribuzione_start=="// 00:00" | $data_distribuzione_start=="00/00/0000 00:00"){$data_distribuzione_start="Non definita...";}
     $data_distribuzione_end = conv_datetime_from_db(data_distribuzione_end_mio_gas($id_ordine,$id_gas));
     if($data_distribuzione_end=="// 00:00" | $data_distribuzione_end=="00/00/0000 00:00"){$data_distribuzione_end="Non definita...";}

     $testo_distribuzione = testo_distribuzione_mio_gas($id_ordine,$id_gas);
     if($testo_distribuzione==""){$testo_distribuzione="Nessuna nota..";}

     // COSTRUZIONE TABELLA  -----------------------------------------------------------------------

     $h_table .=  "<div class=\"rg_widget rg_widget_helper\">
                   <h3>Scheda Ordine</h3>
                    <table>
                        <tr>
                            <td width=\"39%\" style=\"vertical-align:top\">
                                <table>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        Anagrafiche
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Ordine n.<b>$id_ordine</b></th>
                                        <td $col_2>$ordine_nome</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Fornitore</th>
                                        <td $col_2><a href=\"".$RG_addr["form_ditta"]."?id_ditta=$id_ditta\">$ditta</a></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Listino</th>
                                        <td $col_2><a href=\"".$RG_addr["listini_scheda"]."?id_listino=$id_listino\">$listino</a></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Merce trattata</th>
                                        <td $col_2>$tipologia</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Stato:</th>
                                        <td $col_2>$stato<br>$prenota</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Data apertura</th>
                                        <td $col_2>$data_apertura</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Data Chiusura</th>
                                        <td $col_2>$data_chiusura</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Arrivo merce</th>
                                        <td $col_2>$data_merce</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Km percorsi dalla merce</th>
                                        <td $col_2>$gc</td>
                                    </tr>
                                    $sezione_opinioni
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        Situazione ordine
                                        </td>
                                    </tr>
                                    <tr class=\"soldi\">
                                        <th $col_1>Valore totale :                                        </th>
                                        <td $col_2>$valore_totale_ordine Eu.</td>
                                    </tr>
                                    <tr class=\"soldi\">
                                        <th $col_1>Parte Prenotata / Confermata                                        </th>
                                        <td $col_2>$parte_prenotata Eu. / $parte_confermata Eu.</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Articoli ORDINATI / ARRIVATI :                                        </th>
                                        <td $col_2>$articoli_ord / $articoli_arr</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Scatole ORDINATE/ ARRIVATE</th>
                                        <td $col_2>$scatole_intere_ord / $scatole_intere_arr</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Avanzo articoli ORD. / ARR.</th>
                                        <td $col_2>$avanzo_articoli_ord / $avanzo_articoli_arr</td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        Bacino utenze
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Gas coinvolti:</th>
                                        <td $col_2><b>$gas_coinvolti</b> ($bacino_tot Utenti)</div>
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Part. MIO GAS / Tutti i GAS</th>
                                        <td $col_2>$bacino_part_mio_gas / $bacino_part</td>
                                    </tr>

                                </table>
                            </td>
                            <td width=\"39%\" style=\"vertical-align:top\">
                                <table>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        Referenti
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Condizione utente corrente ($io_chi_sono)</th>
                                        <td $col_2>$user_level</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Referente generale ($gas_name_generale)</th>
                                        <td $col_2><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode($id_referente_ordine)."\">$referente_generale</a></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Referenti extra:</th>
                                        <td $col_2 style=\"font-size:.8em\">$lista_gestori_extra</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Aiutanti: $offerta_aiuto</th>
                                        <td $col_2>$aiutanti</td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        $gas_name: Informazioni utili:
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Referente del tuo gas:</th>
                                        <td $col_2><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode($id_referente_proprio_gas)."\">$referente_gas</a></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Distribuzione merce</th>
                                        <td $col_2>dal <b>$data_distribuzione_start</b> al <b>$data_distribuzione_end</b></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Luogo distribuzione:</th>
                                        <td $col_2>$luogo_distribuzione</td>
                                    </tr>

                                    <tr class=\"scheda\">
                                        <th $col_1>Note distribuzione</th>
                                        <td $col_2>$testo_distribuzione</td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        VALORE DELLA MIA SPESA
                                        </td>
                                    </tr>
                                    <tr class=\"soldi\">
                                        <th $col_1>Valore netto attuale</th>
                                        <td $col_2>$valore_personale_attuale_netto_qarr</td>
                                    </tr>
                                    <tr class=\"soldi\">
                                        <th $col_1>Costo trasporto   <b class=\"small_link\">($costo_globale_trasporto $euro)</b></th>
                                        <td $col_2>$costo_trasporto</td>
                                    </tr>
                                    <tr class=\"soldi\">
                                        <th $col_1>Costo Gestione   <b class=\"small_link\">($costo_globale_gestione $euro)</b></th>
                                        <td $col_2>$costo_gestione</td>
                                    </tr>
                                    <tr class=\"soldi\">
                                        <th $col_1>Costo mio gas <b class=\"small_link\">($costo_globale_mio_gas $euro)</b></th>
                                        <td $col_2>".$costo_personale_mio_gas."</td>
                                    </tr>
                                    <tr class=\"soldi\">
                                        <th $col_1>Maggiorazione mio gas del ".$maggiorazione_percentuale_mio_gas."% ".$motivazione_maggiorazione."</th>
                                        <td $col_2>$valore_maggiorazione_mio_gas</td>
                                    </tr>
                                    <tr class=\"soldi grosso\">
                                        <th $col_1>TOTALE ORDINE</th>
                                        <td $col_2>$totale_ordine</td>
                                    </tr>
                                    $j_editable
                                </table>
                            </td>
                        </tr>
                    </table>
                    </div>
                    $dialogo_aiuto";

     if(trim($note_ordine)<>""){

         $h_table .=" <div class=\"rg_widget rg_widget_helper\">
                      <strong>Note ordine</strong>:<div style=\"clear:both\"></div>
                        <div id=\"note_content\" >
                            $note_ordine
                        </div>
                    </div>
                    <br>
                    ";

     }



      // END TABELLA ----------------------------------------------------------------------------



     return $h_table;



    }
    function contabilita_ordine($id_ordine,$id_user=null){
    global $db;
    global $RG_addr;

    $euro = "&#8364";

    $id_gas= id_gas_user($id_user);
    $io_chi_sono = fullname_from_id($id_user);
    $gas_name = gas_nome($id_gas);
    $gas_name_generale = gas_nome(id_gas_user(id_referente_ordine_globale($id_ordine)));

    //ANAGRAFICHE
    $ordine_nome    =   descrizione_ordine_from_id_ordine($id_ordine);
    $id_listino     =   listino_ordine_from_id_ordine($id_ordine);
    $listino        =   listino_nome($id_listino);
    $id_ditta       =   ditta_id_from_listino($id_listino);
    $ditta          =   ditta_nome_from_listino($id_listino);
    $mail_ditta     =   ditta_mail_from_listino($id_listino);
    $tipologia      =   tipologia_nome_from_listino($id_listino);
    $data_apertura  =   conv_datetime_from_db(ordini_field_value($id_ordine,"data_apertura"));
    $data_chiusura  =   conv_datetime_from_db(ordini_field_value($id_ordine,"data_chiusura"));
    $note_ordine    =   ordini_field_value($id_ordine,"note_ordini");



    //ARTICOLI
    $articoli_ord           =   n_articoli_ordinati_da_id_ordine($id_ordine);
    $articoli_arr           =   n_articoli_arrivati_da_id_ordine($id_ordine);
    $scatole_intere_arr     =   q_scatole_intere_ordine_arr($id_ordine);
    $scatole_intere_ord     =   q_scatole_intere_ordine($id_ordine);
    $avanzo_articoli_ord    =   q_articoli_avanzo_ordine($id_ordine);
    $avanzo_articoli_arr    =   q_articoli_avanzo_ordine_arr($id_ordine);

    // RUOLO
    $user_level = "Utente Semplice;<br> ";
        if (id_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user))==$id_user){
                $user_level .= "Referente Proprio GAS;<br> ";
        }
        if (id_referente_ordine_globale($id_ordine)==$id_user){
                $user_level .= "Referente ORDINE; ";
        }
    $id_referente_ordine = id_referente_ordine_globale($id_ordine);
    $referente_generale = fullname_from_id($id_referente_ordine)." (".telefono_from_id($id_referente_ordine).")";
    $referente_gas = fullname_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user))." (".tel_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user)).")";

    //STATO
    $stato_attuale = stato_from_id_ord($id_ordine);
    if($stato_attuale==1){
        $stato = "Programmato";
    }
    if($stato_attuale==2){
        $stato = "Aperto";
    }
    if($stato_attuale==3){
        $stato = "Chiuso - ";
        if(is_printable_from_id_ord($id_ordine)){
            $stato .="<b>STAMPABILE</b>";
        }else{
            $stato .="<b>DA CONFERMARE</b>";
        }
    }

    //BACINO UTENZE
    $bacino_tot = ordine_bacino_utenti($id_ordine);
    $bacino_part = ordine_bacino_utenti_part($id_ordine);
    $bacino_non_part = $bacino_tot-$bacino_part;
    $bacino_percentuale = number_format((($bacino_part/$bacino_tot)*100),1,",","")."%";

    $bacino_tot_mio_gas = gas_n_user($id_gas);
    $bacino_part_mio_gas = ordine_bacino_utenti_part_gas($id_ordine,$id_gas);
    $bacino_non_part_mio_gas = $bacino_tot_mio_gas-$bacino_part_mio_gas;
    $bacino_percentuale_mio_gas = number_format((($bacino_part_mio_gas/$bacino_tot_mio_gas)*100),1,",","")."%";

    $gas_coinvolti=ordine_gas_coinvolti($id_ordine);

    //SPESA ATTUALE
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_miogas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
    $costo_globale_trasporto = valore_trasporto($id_ordine,100);
    $costo_globale_gestione = valore_gestione($id_ordine,100);
    $maggiorazione_percentuale_mio_gas = valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas);

    $costo_globale_mio_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
    $costo_maggiorazione_mio_gas = ($valore_miogas_attuale_netto_qarr /100) * $maggiorazione_percentuale_mio_gas;

    if($maggiorazione_percentuale_mio_gas>0){
        $motivazione_maggiorazione = "(".testo_maggiorazione_mio_gas($id_ordine,$id_gas).")";
    }

    if($valore_globale_attuale_netto_qarr>0){
        $valore_personale_attuale_netto_qarr = valore_totale_mio_ordine($id_ordine,$id_user);
        $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
        if($valore_gas_attuale_netto_qarr>0){
            $percentuale_mio_ordine_gas = ($valore_personale_attuale_netto_qarr / $valore_gas_attuale_netto_qarr) *100;
        }else{
            $percentuale_mio_ordine_gas = 0;
        }
        $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
        $costo_trasporto =  ($costo_globale_trasporto / 100) * $percentuale_mio_ordine;
        $costo_gestione =  ($costo_globale_gestione / 100) * $percentuale_mio_ordine;

        $percentuale_mio_gas = ($valore_miogas_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
        $costo_trasporto_mio_gas = ($costo_globale_trasporto / 100) * $percentuale_mio_gas;
        $costo_gestione_mio_gas = ($costo_globale_gestione / 100) * $percentuale_mio_gas;

        $costo_personale_mio_gas = ($costo_globale_mio_gas /100)*$percentuale_mio_ordine_gas;
        $valore_maggiorazione_mio_gas = ($valore_personale_attuale_netto_qarr / 100) * $maggiorazione_percentuale_mio_gas;




        $totale_ordine =  $valore_personale_attuale_netto_qarr +
                          $costo_trasporto +
                          $costo_gestione +
                          $costo_personale_mio_gas +
                          $valore_maggiorazione_mio_gas ;




    }else{
        $valore_personale_attuale_netto_qarr = 0;
        $costo_trasporto=   0;
        $costo_gestione=    0;
        $costo_personale_mio_gas =0;
        $valore_maggiorazione_mio_gas =0;
    }

        $totale_ordine =    $valore_personale_attuale_netto_qarr +
                            $costo_trasporto +
                            $costo_gestione +
                            $costo_personale_mio_gas +
                            $valore_maggiorazione_mio_gas ;
        $totale_ordine_gas =$valore_miogas_attuale_netto_qarr +
                            $costo_trasporto_mio_gas +
                            $costo_gestione_mio_gas +
                            $costo_globale_mio_gas +
                            $costo_maggiorazione_mio_gas;
        $totale_ordine_pubblico = $valore_globale_attuale_netto_qarr +
                                    $costo_globale_trasporto +
                                    $costo_globale_gestione;
       //FORMATTAZIONE


       $valore_personale_attuale_netto_qarr = number_format((float)round($valore_personale_attuale_netto_qarr,2),2,",","");
       $costo_trasporto = number_format((float)round($costo_trasporto,2),2,",","");
       $costo_gestione  = number_format((float)round($costo_gestione,2),2,",","");
       $costo_personale_mio_gas = number_format((float)round($costo_personale_mio_gas,2),2,",","");
       $valore_maggiorazione_mio_gas = number_format((float)round($valore_maggiorazione_mio_gas,2),2,",","");
       $totale_ordine = number_format((float)round($totale_ordine,2),2,",","");
       $totale_ordine_gas = number_format((float)round($totale_ordine_gas,2),2,",","");
       $totale_ordine_pubblico = number_format((float)round($totale_ordine_pubblico,2),2,",","");

       $maggiorazione_percentuale_mio_gas = number_format((float)round($maggiorazione_percentuale_mio_gas,2),2,",","");
       $costo_globale_mio_gas = number_format((float)round($costo_globale_mio_gas,2),2,",","");
       $costo_globale_trasporto = number_format((float)round($costo_globale_trasporto,2),2,",","");
       $costo_globale_gestione = number_format((float)round($costo_globale_gestione,2),2,",","");
       $articoli_ord = (float)round($articoli_ord,2);
       $articoli_arr = (float)round($articoli_arr,2);
       $valore_globale_attuale_netto_qarr =  number_format((float)round($valore_globale_attuale_netto_qarr,2),2,",","");
       $valore_miogas_attuale_netto_qarr = number_format((float)round($valore_miogas_attuale_netto_qarr,2),2,",","");

       $percentuale_mio_ordine_gas = number_format((float)round($percentuale_mio_ordine_gas,2),2,",","");
       $percentuale_mio_ordine = number_format((float)round($percentuale_mio_ordine,2),2,",","");
       $percentuale_mio_gas = number_format((float)round($percentuale_mio_gas,2),2,",","");

       $costo_trasporto_mio_gas = number_format((float)round($costo_trasporto_mio_gas,2),2,",","");
       $costo_gestione_mio_gas = number_format((float)round($costo_gestione_mio_gas,2),2,",","");
       $costo_maggiorazione_mio_gas = number_format((float)round($costo_maggiorazione_mio_gas,2),2,",","");



       // COSTRUZIONE TABELLA  -----------------------------------------------------------------------

     //formattazione colonna percentale
     //$col_3 = " style=\"border-bottom:0; text-align:center;\" ";

     $h_table .=  " <div class=\"rg_widget rg_widget_helper\">
                    <table>
                        <tr>
                            <td width=\"100%\">
                                <table>
                                    <tr class=\"titolino\">
                                        <td colspan=7>Valore Netto</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1></th>
                                        <th colspan=2 $col_1>Mia Spesa</th>
                                        <th colspan=2 $col_1>Mio Gas</th>
                                        <th colspan=2 $col_1>Ordine</th>
                                    </tr>
                                    <tr class=\"scheda soldi\">
                                        <th $col_1>Articoli Ordinati</th>
                                        <td $col_3>$percentuale_mio_ordine %</td>
                                        <td $col_2>$valore_personale_attuale_netto_qarr $euro</td>
                                        <td $col_3>$percentuale_mio_gas %</td>
                                        <td $col_2>$valore_miogas_attuale_netto_qarr $euro</td>
                                        <td $col_3>100,00 %</td>
                                        <td $col_2><b>$valore_globale_attuale_netto_qarr $euro</b></td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=7>Costi Pubblici</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1></th>
                                        <th colspan=2 $col_1>Mia Spesa</th>
                                        <th colspan=2 $col_1>Mio Gas</th>
                                        <th colspan=2 $col_1>Ordine</th>
                                    </tr>
                                    <tr class=\"scheda soldi\">
                                        <th $col_1>Trasporto</th>
                                        <td $col_3>$percentuale_mio_ordine %</td>
                                        <td $col_2>$costo_trasporto $euro</td>
                                        <td $col_3>$percentuale_mio_gas %</td>
                                        <td $col_2>$costo_trasporto_mio_gas $euro</td>
                                        <td $col_3>100,00 %</td>
                                        <td $col_2><b>$costo_globale_trasporto $euro</b></td>
                                    </tr>
                                    <tr class=\"scheda soldi\">
                                        <th $col_1>Gestione</th>
                                        <td $col_3>$percentuale_mio_ordine %</td>
                                        <td $col_2>$costo_gestione $euro</td>
                                        <td $col_3>$percentuale_mio_gas %</td>
                                        <td $col_2>$costo_gestione_mio_gas $euro</td>
                                        <td $col_3>100,00 %</td>
                                        <td $col_2><b>$costo_globale_gestione $euro</b></td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=7>Costi Privati</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1></th>
                                        <th colspan=2 $col_1>Mia Spesa</th>
                                        <th colspan=2 $col_1>Mio Gas</th>
                                        <th colspan=2 $col_1>Ordine</th>
                                    </tr>
                                    <tr class=\"scheda soldi\">
                                        <th $col_1>Costo Proprio GAS</th>
                                        <td $col_3>$percentuale_mio_ordine_gas %</td>
                                        <td $col_2>$costo_personale_mio_gas $euro</td>
                                        <td $col_3>100,00 %</td>
                                        <td $col_2><b>$costo_globale_mio_gas $euro</b></td>
                                        <td $col_3></td>
                                        <td $col_2></td>
                                    </tr>
                                    <tr class=\"scheda soldi\">
                                        <th $col_1>Maggiorazione Proprio GAS</th>
                                        <td $col_3>$maggiorazione_percentuale_mio_gas %</td>
                                        <td $col_2>$valore_maggiorazione_mio_gas $euro</td>
                                        <td $col_3><b>$maggiorazione_percentuale_mio_gas %</b></td>
                                        <td $col_2>$costo_maggiorazione_mio_gas $euro</td>
                                        <td $col_3></td>
                                        <td $col_2></td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=7>Totali</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1></th>
                                        <th colspan=2 $col_1>Mia Spesa</th>
                                        <th colspan=2 $col_1>Mio Gas</th>
                                        <th colspan=2 $col_1>Ordine</th>
                                    </tr>
                                    <tr class=\"scheda soldi\">
                                        <th $col_1></th>
                                        <td $col_3></td>
                                        <td $col_2>$totale_ordine $euro</td>
                                        <td $col_3></td>
                                        <td $col_2>$totale_ordine_gas $euro</td>
                                        <td $col_3></td>
                                        <td $col_2>$totale_ordine_pubblico $euro</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>";




      // END TABELLA ----------------------------------------------------------------------------



     return $h_table;



    }

//MODIFICA
function modifica_quantita_articoli_ordine($id_arti,$ordine,$id_user,$q_min,$id_dett = null){

      global $RG_addr;
      global $db, $v1,$v2,$v3,$v4,$v5;
      global $a_hdr,$a_std,$a_tot,$a_nto,$a_cnt;
      global $stili;
//echo "id arti = ".$id_arti;
//echo "ordine = ".$ordine;
//echo "user = ".$id_user;

$query ="SELECT
retegas_amici.id_amici,
retegas_amici.nome,
retegas_amici.id_referente
FROM
retegas_amici
WHERE
retegas_amici.id_referente =  '$id_user'
AND retegas_amici.is_visible = '1'
AND retegas_amici.status = '1'";
$result = $db->sql_query($query);


//$output_html .= "<br />";
//$output_html .= "Multiplo Minimo : ".$q_min;

// N RIGA
if(empty($id_dett)){
$sql_riga="SELECT
*
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_articoli =  '$id_arti' AND
retegas_dettaglio_ordini.id_ordine =  '$ordine' AND
retegas_dettaglio_ordini.id_utenti =  '$id_user';";
}else{

$sql_riga="SELECT
*
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_dettaglio_ordini =  '$id_dett';";

}
$ret_riga = mysql_query($sql_riga);
$row_riga = mysql_fetch_row($ret_riga);
// N RIGA



//$n_riga = n_riga_ordini_dettaglio_distribuzione($ordine,$id_arti,$id_user);
 $n_riga=$row_riga[0];
//echo "Appena munto n_riga = $n_riga";





$output_html .= "<table>";

 //HEADER
        unset ($d);
        $i=0;
        $cm= $a_hdr; // HEADER - CLASSE MADRE

        $d[$i][0]="Assegnatario";  $i++;
        $d[$i][0]="Quantita' ordinata";  $i++;
        //$d[$i][0]="Nuova Quantita'";  $i++;
        $output_html .= "<tr class=\"odd\">
                            <th>Assegnatario</th>
                            <th>Quantità da ordinare</td>
                         </tr>
                         ";
        // HEADER
$output_html .="<form method=\"POST\" action=\"ordini_aperti_mod_q.php\">";

$riga=0;



// RIGA DEL ME STESSO
        //$c0=$row["id_riga_dettaglio_ordine"];
        $c1="<b>Me stesso</b>";
        //$c3=$row["id_dettaglio_ordini"];
        //$c15=articoli_per_amici($c0,$c2);
        $c_amico = 0;
        $c2=n_articoli_ordini_dettaglio_distribuzione_ord($ordine,$id_arti,0,$id_user,$id_dett);
        //echo "----$ordine, $id_arti, $id_user, C2 = $c2";
        $c20="<input type=\"text\" name=nuova_q[] value=\"$c2\" size=\"3\"><input type=\"hidden\" name=amico[] value=\"$c_amico\" size=\"3\">";

        //if(empty($c15)){$c15="0";};

    unset ($d);
               $i=0;
               $cm = $a_std;   // CLASSE MADRE = STANDARD



               //$d[$i][0]=$c1;           $d[$i][1]="";               $d[$i][2]="";      $i++;
               $d[$i][0]=$c1;          $d[$i][1]="";               $d[$i][2]="";      $i++;
               $d[$i][0]=$c20;          $d[$i][1]="";               $d[$i][2]="";      $i++;

               $output_html .= "<tr class=\"odd\">
                                <td>$c1</td>
                                <td>$c20</td>
                                </tr>";


               $riga++;


// RIGA DEL ME STESSO

while ($row = mysql_fetch_array($result)){
        //$c0=$row["id_riga_dettaglio_ordine"];
        $c1=$row["nome"];
        $c3=$row["id_dettaglio_ordini"];
        //$c15=articoli_per_amici($c0,$c2);
        $c_amico = $row["id_amici"];
        $c2=n_articoli_ordini_dettaglio_distribuzione_ord($ordine,$id_arti,$row["id_amici"],$id_user,$id_dett);
        //echo "----'''''$ordine, $id_arti, $id_user, C2 = $c2";
        $c20="<input type=\"text\" name=nuova_q[] value=\"$c2\" size=\"3\"><input type=\"hidden\" name=amico[] value=\"$c_amico\" size=\"3\">";

        //if(empty($c15)){$c15="0";};

    unset ($d);
               $i=0;
               $cm = $a_std;   // CLASSE MADRE = STANDARD



               //$d[$i][0]=$c1;           $d[$i][1]="";               $d[$i][2]="";      $i++;
               $d[$i][0]=$c1;          $d[$i][1]="";               $d[$i][2]="";      $i++;
               $d[$i][0]=$c20;          $d[$i][1]="";               $d[$i][2]="";      $i++;

               // ----------------$output_html .= r_rt2($cm,$d,$riga,2);
                $output_html .= "<tr class=\"odd\">
                                <td>$c1</td>
                                <td>$c20</td>
                                </tr>";


               $riga++;

}

$output_html .= "</table>";
if(empty($id_dett)){
$operazione = "do_mod_q";
}else{
 $operazione = "do_mod_q_uni";
}

$output_html .= "<input type=\"hidden\" name=\"id_articolo\" value=\"$id_arti\">
                <input type=\"hidden\" name=\"q_min\" value=\"$q_min\">
               <input type=\"hidden\" name=\"do\" value=\"$operazione\">
               <input type=\"hidden\" name=\"id_ordine\" value=\"$ordine\">
               <input type=\"hidden\" name=\"n_riga\" value=\"$n_riga\">
               <input class=\"large green awesome destra\" style=\"margin:20px;\"type=\"submit\" value=\"Salva i nuovi quantitativi !\"></center>";



return $output_html;

}
function modifica_quantita_articoli_ordine_new($id_arti,$ordine,$id_user,$q_min){

    global $db,$v1,$v2,$v3,$v4,$v5;
      global $a_hdr,$a_std,$a_tot,$a_nto,$a_cnt;
      global $stili;


// STAMPO LA TABELLA PER AGGIUNGERE ARTICOLI


// QUI HO TUTTI I MIEI AMICI
$query ="SELECT
retegas_dettaglio_ordini.id_dettaglio_ordini,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.codice,
retegas_articoli.descrizione_articoli,
retegas_dettaglio_ordini.qta_ord,
retegas_articoli.qta_minima
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_utenti =  '$id_user' AND
retegas_dettaglio_ordini.id_ordine =  '$ordine' AND
retegas_dettaglio_ordini.id_articoli =  '$id_arti'";
$result = $db->sql_query($query);

$titolo_tabella = "Articolo unico";



$h_table .= "
      <div class=\"rg_widget rg_widget_helper\">
      <form method=\"POST\" action=\"ordini_aperti_mod_q.php\">
      <div style =\"margin-bottom:10px;\">Articolo unico, scegli quale tra questi vuoi assegnare agli amici</div>
      <table id=\"spesa\" \">

        <tr class=\"odd\">
            <th>Codice GAS</th>
            <th>Codice</th>
            <th>Descrizione</th>
            <th>Quantità Ordinata</th>
            <th>Assegnatari</th>
            <th>Operazioni</th>
        </tr>";
//RIGA PER AGGIUNGERE QUANTITATIVO ALL'ARTICOLO.
$c20="<input type=\"text\" name=\"q_to_add\" value=\"0\" size=\"3\">
       <input type=\"hidden\" name=\"id_arti\" value=\"$id_arti\">
       <input type=\"hidden\" name=\"q_min\" value=\"$q_min\">
       <input type=\"hidden\" name=\"do\" value=\"do_add_q\">
       <input type=\"hidden\" name=\"id_ordine\" value=\"$ordine\">";
$h_table .='<tr class="odd">
          <td>'.$id_arti.'</td>
          <td></td>
          <td></td>
          <td>'.$c20.'</td>
          <td>[Me stesso]</td>
          <td style="text-align:right;" width="15%"><input class="small green awesome" type="submit" value="Aggiungi"></td>
          </tr>';


while ($row = mysql_fetch_array($result)){
$do_del = "ordini_aperti_mod_q.php?do=do_del_riga&n_riga=".$row["id_dettaglio_ordini"]."&id_ordine=".$ordine;
$assegnatari = lista_assegnatari_articolo_dettaglio($row["id_dettaglio_ordini"]);
$operazione = ' <a class="awesome yellow small" href="ordini_aperti_mod_q.php?id='.$row["id_articoli"].'&id_ordine='.$ordine.'&q_min='.$q_min.'&id_dett='.$row["id_dettaglio_ordini"].'" title="Modifica">M</a>
                <a class="awesome black small" href="'.$do_del.'" title="Cancella">C</a>';
$h_table .='<tr class="odd">
          <td>'.$row["id_articoli"].' ('.$row["id_dettaglio_ordini"].')</td>
          <td>'.$row["codice"].'</td>
          <td>'.$row["descrizione_articoli"].'</td>
          <td>'.$row["qta_ord"].'</td>
          <td>'.$assegnatari.'</td>
          <td style="text-align:right;" width="15%">'.$operazione.'</td>
          </tr>';

}



$h_table .= "</table>
             </form>
             </div>";


return $h_table;

}
function modifica_quantita_articoli_univoci($id_articolo,$id_ordine,$id_user,$q_min){



// QUI HO TUTTI I MIEI AMICI
$query ="SELECT
retegas_dettaglio_ordini.id_dettaglio_ordini,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.codice,
retegas_articoli.descrizione_articoli,
retegas_dettaglio_ordini.qta_ord,
retegas_articoli.qta_minima
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_utenti =  '$id_user' AND
retegas_dettaglio_ordini.id_ordine =  '$ordine' AND
retegas_dettaglio_ordini.id_articoli =  '$id_arti'";
$result = $db->sql_query($query);

$titolo_tabella = "Articolo unico";



$h_table .= "
      <div class=\"rg_widget rg_widget_helper\">
      <form method=\"POST\" action=\"ordini_aperti_mod_q.php\">
      <div style =\"margin-bottom:10px;\">Articolo unico, scegli quale tra questi vuoi assegnare agli amici</div>
      <table id=\"spesa\" \">

        <tr class=\"odd\">
            <th>Codice GAS</th>
            <th>Codice</th>
            <th>Descrizione</th>
            <th>Quantità Ordinata</th>
            <th>Assegnatari</th>
            <th>Operazioni</th>
        </tr>";
//RIGA PER AGGIUNGERE QUANTITATIVO ALL'ARTICOLO.
$c20="<input type=\"text\" name=\"q_to_add\" value=\"0\" size=\"3\">
       <input type=\"hidden\" name=\"id_arti\" value=\"$id_arti\">
       <input type=\"hidden\" name=\"q_min\" value=\"$q_min\">
       <input type=\"hidden\" name=\"do\" value=\"do_add_q\">
       <input type=\"hidden\" name=\"id_ordine\" value=\"$ordine\">";
$h_table .='<tr class="odd">
          <td>'.$id_arti.'</td>
          <td></td>
          <td></td>
          <td>'.$c20.'</td>
          <td>[Me stesso]</td>
          <td style="text-align:right;" width="15%"><input class="small green awesome" type="submit" value="Aggiungi"></td>
          </tr>';


while ($row = mysql_fetch_array($result)){
$do_del = "ordini_aperti_mod_q.php?do=do_del_riga&n_riga=".$row["id_dettaglio_ordini"]."&id_ordine=".$ordine;
$assegnatari = lista_assegnatari_articolo_dettaglio($row["id_dettaglio_ordini"]);
$operazione = ' <a class="awesome yellow small" href="ordini_aperti_mod_q.php?id='.$row["id_articoli"].'&id_ordine='.$ordine.'&q_min='.$q_min.'&id_dett='.$row["id_dettaglio_ordini"].'" title="Modifica">M</a>
                <a class="awesome black small" href="'.$do_del.'" title="Cancella">C</a>';
$h_table .='<tr class="odd">
          <td>'.$row["id_articoli"].' ('.$row["id_dettaglio_ordini"].')</td>
          <td>'.$row["codice"].'</td>
          <td>'.$row["descrizione_articoli"].'</td>
          <td>'.$row["qta_ord"].'</td>
          <td>'.$assegnatari.'</td>
          <td style="text-align:right;" width="15%">'.$operazione.'</td>
          </tr>';

}



$h_table .= "</table>
             </form>
             </div>";


return $h_table;

}

//ORDINE SCHEDINA
function ordine_schedina_widget($id_ordine){
    global $db;

        if (id_referente_ordine_globale($id_ordine)<>_USER_ID){
                return "Questo widget serve solo ai gestori degli ordini.";
        }


       $result = $db->sql_query("SELECT
                                retegas_dettaglio_ordini.data_inserimento,
                                maaking_users.userid,
                                maaking_users.fullname,
                                retegas_gas.descrizione_gas,
                                maaking_users.id_gas,
                                retegas_articoli.codice,
                                retegas_articoli.descrizione_articoli,
                                retegas_dettaglio_ordini.qta_ord,
                                retegas_articoli.u_misura,
                                retegas_articoli.misura,
                                retegas_articoli.prz_dett_arr
                                FROM
                                retegas_dettaglio_ordini
                                Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                                Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                                Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                                WHERE
                                retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
                                ORDER BY
                                retegas_dettaglio_ordini.data_inserimento DESC");


       $riga=0;
         while ($row = $db->sql_fetchrow($result)){


              $dataora = conv_datetime_from_db($row["data_inserimento"]);
              $id_ut = $row["userid"];
              $nome_ut = $row['fullname'];
              $gas_app = $row['descrizione_gas'];
              $id_gas_app = $row['id_gas'];
              $codice = $row['codice'];
              $descrizione = $row['descrizione_articoli'];
              $q_ord = _nf($row["qta_ord"]);
              $u_mis = $row["u_misura"];
              $mis = $row["misura"];
              $prezzo = _nf($row["prezzo"]);


              $h.= "<ul>";
                $h.= "<li><strong>$dataora, $nome_ut</strong>, <span class=\"small_link\">$gas_app</span></li>";
                $h.= "<li>$codice, $descrizione <span class=\"small_link\">($u_mis $mis x $euro $prezzo) x $q_ord</span></li>";
              $h .="</ul>";

         }//end while



  return $h;






   return "";
}

//VARIE PER ORDINI
function ultimo_ordine_immesso($idu){
  //IDu = nulla
  $sql = "SELECT retegas_ordini.id_ordini FROM retegas_ordini ORDER BY retegas_ordini.id_ordini DESC LIMIT 1;";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function fullname_ref_gas_ordine($id_ordine,$id_gas){
$sql="SELECT
maaking_users.fullname
FROM
maaking_users
Inner Join retegas_referenze ON retegas_referenze.id_utente_referenze = maaking_users.userid
WHERE
retegas_referenze.id_ordine_referenze =  '$id_ordine' AND
retegas_referenze.id_gas_referenze =  '$id_gas'
LIMIT 1";
$ret = mysql_query($sql);
$row = mysql_fetch_row($ret);
return $row[0];

}
function dettagli_ordine($idu){
  //ID ordine --> Quanti dettagli associati
  $sql = "SELECT * FROM `retegas_dettaglio_ordini` WHERE (`retegas_dettaglio_ordini`.`id_ordine`= '$idu');";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;

}
function ordini_user($idu){
  //ID User --> Quanti ordini ha come referente globale
  $sql = "SELECT retegas_ordini.id_utente FROM retegas_ordini WHERE (((retegas_ordini.id_utente)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_num_rows($ret);
  return $row;
}
function ordini_user_partecipato($idu){
  //ID User --> Quanti ordini ha come referente globale
  $sql = "SELECT id_utenti, id_ordine FROM retegas_dettaglio_ordini WHERE (((id_utenti)='$idu')) GROUP BY id_ordine;";
  $ret = mysql_query($sql);
  $row = mysql_num_rows($ret);
  return $row;
}
function quanti_ordini_per_questo_listino($idu){
  //ID listino --> Quanti ordini ha associati
  $sql = "SELECT *
FROM
retegas_ordini
WHERE
retegas_ordini.id_listini =  '$idu'";
  $ret = mysql_query($sql);
  $row = mysql_num_rows($ret);
  return $row;
}
function quanti_ordini_aperti_per_questa_tipologia($idu){

  $sql = "SELECT
Count(retegas_ordini.id_ordini)
FROM
retegas_listini
Inner Join retegas_ordini ON retegas_ordini.id_listini = retegas_listini.id_listini
WHERE
retegas_listini.id_tipologie =  '$idu'
AND
retegas_ordini.id_stato='2';";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function quanti_ordini_chiusi_per_questa_tipologia($idu){

  $sql = "SELECT
Count(retegas_ordini.id_ordini)
FROM
retegas_listini
Inner Join retegas_ordini ON retegas_ordini.id_listini = retegas_listini.id_listini
WHERE
retegas_listini.id_tipologie =  '$idu'
AND
retegas_ordini.id_stato<>'2';";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function quanti_listini_per_questa_tipologia($idu){

  $sql = "SELECT
Count(retegas_listini.id_listini)
FROM
retegas_listini
WHERE
retegas_listini.id_tipologie =  '$idu';";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function ordini_gas_user($idu){
  //ID User --> Quanti ordini ha come referente globale
  $sql = "SELECT * FROM retegas_referenze WHERE (((retegas_referenze.id_utente_referenze)=$idu));";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
Function ordine_bacino_utenti_part($ord){
$sql="SELECT
count(retegas_dettaglio_ordini.id_utenti)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ord'
GROUP BY
retegas_dettaglio_ordini.id_utenti";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
Function ordine_gas_coinvolti($ord){
$sql="SELECT
Count(retegas_referenze.id_referenze)
FROM
retegas_referenze
WHERE
retegas_referenze.id_ordine_referenze =  '$ord'";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function ordine_io_cosa_sono($ordine,$id_user){
//echo "ORDINE :".$ordine." - ".$id_user."<br>";
$cosa = 0;
$mio_gas = id_gas_user($id_user);


$sql = "SELECT * FROM retegas_referenze
WHERE (((retegas_referenze .id_ordine_referenze)='$ordine')
        AND (retegas_referenze.id_gas_referenze='$mio_gas'));";
  $ret = mysql_query($sql);
  $nrow2 = mysql_numrows($ret);

if ($nrow2>0){
    if(ordine_partecipabile($ordine)){
        $cosa=1;
    }
}
$sql = "SELECT * FROM retegas_dettaglio_ordini
        WHERE ((retegas_dettaglio_ordini.id_ordine='$ordine')
        AND (retegas_dettaglio_ordini.id_utenti='$id_user'));";
  $ret = mysql_query($sql);
  $nrow = mysql_numrows($ret);

if ($nrow>0){
    $cosa=2;
}

if(id_referente_ordine_proprio_gas($ordine,$mio_gas)==$id_user){
    $cosa=3;
}

if(id_referente_ordine_globale($ordine)==$id_user){
    $cosa=4;
}





//echo "COSA = ".$cosa;
return $cosa;


    // 0 = non c'entro
    // 1 = possibile utente
    // 2 = utente
    // 3 = referente GAS
    // 4 = referente ORDINE

}
function ordine_inesistente($ordine){
$sql = "SELECT * FROM retegas_ordini
WHERE ((retegas_ordini.id_ordini)='$ordine');";
  $ret = mysql_query($sql);
  $nrow2 = mysql_numrows($ret);
if($nrow2>0){
    return false;
    exit;
}else{
return true;
    exit;

}
}
function ordine_partecipabile($ordine){
$sql = "SELECT * FROM retegas_ordini
WHERE ((retegas_ordini.id_ordini)='$ordine')
AND ((retegas_ordini.data_chiusura)>NOW())
AND ((retegas_ordini.data_apertura)<NOW());";
  $ret = mysql_query($sql);
  $nrow2 = mysql_numrows($ret);
if($nrow2>0){
    return true;
    exit;
}else{
return false;
    exit;

}
}
function stato_from_id_ord($id_ord){
  //ID user --> fullname

  $sql = "SELECT retegas_ordini.id_stato
        FROM retegas_ordini
        WHERE (((retegas_ordini.id_ordini)='$id_ord'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function livello_rompimento_ordine($id_ord){
  //ID user --> fullname

  $sql = "SELECT retegas_ordini.mail_level
        FROM retegas_ordini
        WHERE (((retegas_ordini.id_ordini)='$id_ord'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function gas_partecipa_ordine($id_ordine,$id_gas){
    global $db;
    $query = "SELECT id_utente_referenze FROM retegas_referenze WHERE id_ordine_referenze='$id_ordine' AND id_gas_referenze='$id_gas' LIMIT 1";

    $res = $db->sql_query($query,FALSE,"gas_partecipa_ordine");

    $row = $db->sql_fetchrow($res);
    $n_row = $db->sql_numrows($res);

    if($n_row>0){
        if($row[0]=="0"){
              return "1";
              exit;
        }else{
              return "2";
              exit;
        }

    }else{
        return "0";
        exit;
    }
}
function is_printable_from_id_ord($id_ord){
  //ID user --> fullname

  $sql = "SELECT retegas_ordini.is_printable
        FROM retegas_ordini
        WHERE (((retegas_ordini.id_ordini)='$id_ord'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  if($row[0]==1){
  return true;
  }else{
  return false;
  }


}
function ordine_cassa_obbligatoria($id_ord){
  //ID user --> fullname

  $sql = "SELECT retegas_ordini.solo_cassati
        FROM retegas_ordini
        WHERE (((retegas_ordini.id_ordini)='$id_ord'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  if($row[0]=="si"){
    return true;
  }else{
    return false;
  }


}

function n_articoli_ordini_user($idu,$ordine){
  //Quanti articoli ha in ordine id_user
  $idu = intval($idu);
  $ordine = intval($ordine);

  $sql = "SELECT * FROM retegas_dettaglio_ordini WHERE ((retegas_dettaglio_ordini.id_utenti=$idu) AND (retegas_dettaglio_ordini.id_ordine=$ordine));";
  $ret = mysql_query($sql);
  $row = mysql_num_rows($ret);
  return $row;
}
function n_articoli_ordini_dettaglio_distribuzione($ordine,$id_articolo,$id_amico,$id_user){
$sql="SELECT
retegas_distribuzione_spesa.qta_arr
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_amico =  '$id_amico' AND
retegas_distribuzione_spesa.id_ordine =  '$ordine' AND
retegas_distribuzione_spesa.id_articoli =  '$id_articolo' AND
retegas_distribuzione_spesa.id_user =  '$id_user'
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];


}
function n_articoli_arr_dettaglio_distribuzione_n_riga($id_amico,$n_riga){
$sql="SELECT
retegas_distribuzione_spesa.qta_arr
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_amico =  '$id_amico' AND
retegas_distribuzione_spesa.id_riga_dettaglio_ordine = '$n_riga'
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function n_articoli_ord_dettaglio_distribuzione_n_riga($id_amico,$n_riga){
$sql="SELECT
retegas_distribuzione_spesa.qta_ord
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_amico =  '$id_amico' AND
retegas_distribuzione_spesa.id_riga_dettaglio_ordine = '$n_riga'
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return CAST_TO_FLOAT($row[0]);
}



//ARTICOLI ARRIVATI
function n_articoli_arrivati_da_id_ordine($id_ordine){

global $db;
global $class_debug;

$sql="SELECT
sum(retegas_dettaglio_ordini.qta_arr)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_ordine = '$id_ordine';";
$ret = $db->sql_query($sql);
$row = mysql_fetch_row($ret);

$class_debug->debug_msg[] = "FUN : n_articoli_arrivati_da_id_ordine id_ordine=$id_ordine, result = ".$row[0];
return $row[0];

}
function n_articoli_arrivati_da_id_dett($id_dett){
$sql="SELECT
sum(retegas_dettaglio_ordini.qta_arr)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_dettaglio_ordini = '$id_dett'
GROUP BY
retegas_dettaglio_ordini.id_articoli
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];

}
function n_articoli_arrivati_da_user($ordine,$id_articolo,$id_user){
$sql="SELECT
sum(retegas_dettaglio_ordini.qta_arr)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine' AND
retegas_dettaglio_ordini.id_articoli =  '$id_articolo' AND
retegas_dettaglio_ordini.id_utenti =  '$id_user';";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return round($row[0]);


}

//ARTICOLI ORDINATI
function n_articoli_ordinati_da_id_ordine($id_ordine){

global $db;
global $class_debug;

$sql="SELECT
sum(retegas_dettaglio_ordini.qta_ord)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_ordine = '$id_ordine';";
$ret = $db->sql_query($sql);
$row = mysql_fetch_row($ret);

$class_debug->debug_msg[] = "FUN : n_articoli_ordinati_da_id_ordine id_ordine=$id_ordine, result = ".$row[0];
return $row[0];

}
function n_articoli_ordinati_da_id_dett($id_dett){
$sql="SELECT
sum(retegas_dettaglio_ordini.qta_ord)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_dettaglio_ordini = '$id_dett'
GROUP BY
retegas_dettaglio_ordini.id_articoli
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];

}
function n_articoli_ordinati_da_user($ordine,$id_articolo,$id_user){
$sql="SELECT
sum(retegas_dettaglio_ordini.qta_ord)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine' AND
retegas_dettaglio_ordini.id_articoli =  '$id_articolo' AND
retegas_dettaglio_ordini.id_utenti =  '$id_user'
GROUP BY
retegas_dettaglio_ordini.id_articoli
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];


}
function n_articoli_ordinati_da_amico($id_ordine,$id_utente,$id_amico,$id_articolo){

//echo "id ordine : $id_ordine<br>";
//echo "id amico : $id_amico<br>";
//echo "id utente : $id_utente<br>";
//echo "id articolo : $id_articolo<br>";

global $db;

$sql = "SELECT sum(qta_ord) FROM retegas_distribuzione_spesa
        WHERE id_articoli = '$id_articolo'
        AND id_user='$id_utente'
        AND id_amico ='$id_amico'
        AND id_ordine ='$id_ordine';";
$res = $db->sql_query($sql);
$row = $db->sql_fetchrow($res);

return round($row[0],4);

}


//ARTICOLI ARRIVATI

function q_articoli_avanzo_ordine($ordine){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
(Sum(retegas_dettaglio_ordini.qta_ord)),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
GROUP BY
retegas_dettaglio_ordini.id_articoli;";

  $ret = mysql_query($sql);
  $avanzo=0;
  while ($row = mysql_fetch_row($ret))
  {
          (float)$somma = $row[0];
          (float)$sottrarre = $row[4];

          //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

          if (empty($somma)){
             // echo "Art : $row[1] Somma = a zero<br>";
              $somma=0;
          }

          //if ($somma < $sottrarre){
              //echo "Art : $articolo Somma minore di sottrarre";
          //      $somma=0;
          //}

          while ($somma >= $sottrarre){
                $somma = ($somma - $sottrarre);
          }

          $avanzo = $avanzo + $somma;
          //echo "Articolo $row[1] Avanzo = $avanzo<br>";
  }

  return $avanzo;

}
function q_articoli_avanzo_ordine_arr($ordine){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
(Sum(retegas_dettaglio_ordini.qta_arr)),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
GROUP BY
retegas_dettaglio_ordini.id_articoli;";

  $ret = mysql_query($sql);
  $avanzo=0;
  while ($row = mysql_fetch_row($ret))
  {
          (float)$somma = $row[0];
          (float)$sottrarre = $row[4];

          //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

          if (empty($somma)){
             // echo "Art : $row[1] Somma = a zero<br>";
              $somma=0;
          }

          //if ($somma < $sottrarre){
              //echo "Art : $articolo Somma minore di sottrarre";
          //      $somma=0;
          //}

          while ($somma >= $sottrarre){
                $somma = round(($somma - $sottrarre),3);
          }

          $avanzo = $avanzo + $somma;
          //echo "Articolo $row[1] Avanzo = $avanzo<br>";
  }

  return $avanzo;

}

function q_scatole_intere_ordine($ordine){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
(Sum(retegas_dettaglio_ordini.qta_ord)),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
GROUP BY
retegas_dettaglio_ordini.id_articoli;";

  $ret = mysql_query($sql);
  $avanzo=0;
  $scatole=0;

  while ($row = mysql_fetch_row($ret))
  {


          (float)$somma = $row[0];
          (float)$sottrarre = $row[4];

          //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

          if (empty($somma)){
              //echo "Art : $articolo Somma = a zero";
              //break;
              $somma=0;
          }

          if ($somma < $sottrarre){
              //echo "Art : $articolo Somma minore di sottrarre";
              $somma=0;
          }

          while ($somma > 0){
                //echo "$somma meno $sottrarre";
                $somma = ($somma - $sottrarre);

                if($somma>=0){
                    $scatole++;
                }
                //echo " UGUALE $somma , scatole = $scatole<br>";
          }



  }

  return (int)$scatole;
}
function q_scatole_intere_ordine_arr($ordine){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
(Sum(retegas_dettaglio_ordini.qta_arr)),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
GROUP BY
retegas_dettaglio_ordini.id_articoli;";

  $ret = mysql_query($sql);
  $avanzo=0;
  $scatole=0;

  while ($row = mysql_fetch_row($ret))
  {


          (float)$somma = $row[0];
          (float)$sottrarre = $row[4];

          //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

          if (empty($somma)){
              //echo "Art : $articolo Somma = a zero";
              //break;
              $somma=0;
          }

          if ($somma < $sottrarre){
              //echo "Art : $articolo Somma minore di sottrarre";
              $somma=0;
          }

          while ($somma > 0){
                //echo "$somma meno $sottrarre";
                $somma = ($somma - $sottrarre);

                if($somma>=0){
                    $scatole++;
                }
                //echo " UGUALE $somma , scatole = $scatole<br>";
          }



  }

  return (int)$scatole;
}

function q_scatole_intere_articolo_ordine($ordine,$articolo){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
Sum(retegas_dettaglio_ordini.qta_ord),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
AND
retegas_dettaglio_ordini.id_articoli =  '$articolo'
GROUP BY
retegas_dettaglio_ordini.id_articoli
LIMIT 1";

  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);

  $scatole=0;

  (float)$somma = $row[0];
  (float)$sottrarre = $row[4];

 // Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

  if (empty($somma)){
      //echo "Art : $articolo Somma = a zero";
      return 0;
      exit;
  }

  if ($somma < $sottrarre){
      //echo "Art : $articolo Somma minore di sottrarre";
      return 0;
      exit;
  }



  while ($somma > 0){


        //echo "$somma meno $sottrarre";
        $somma = ($somma - $sottrarre);

        if($somma>=0){
            $scatole++;
        }

        //echo " UGUALE $somma , scatole = $scatole<br>";


  }
  return (int)$scatole;
}
function q_scatole_intere_articolo_ordine_arr($ordine,$articolo){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
Sum(retegas_dettaglio_ordini.qta_arr),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
AND
retegas_dettaglio_ordini.id_articoli =  '$articolo'
GROUP BY
retegas_dettaglio_ordini.id_articoli
LIMIT 1";

  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);

  $scatole=0;

  (float)$somma = $row[0];
  (float)$sottrarre = $row[4];

  //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

  if (empty($somma)){
      //echo "Art : $articolo Somma = a zero";
      return 0;
      exit;
  }

  if ($somma < $sottrarre){
      //echo "Art : $articolo Somma minore di sottrarre";
      return 0;
      exit;
  }

  while ($somma > 0){


        //echo "$somma meno $sottrarre";
        $somma = ($somma - $sottrarre);

        if($somma>=0){
            $scatole++;
        }

        //echo " UGUALE $somma , scatole = $scatole<br>";


  }
  return (int)$scatole;
}
function q_scatole_intere_articolo_singolo($scatola,$q){
      $scatole=0;

  (float)$somma = $q;
  (float)$sottrarre = $scatola;

  //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

  if (empty($somma)){
      //echo "Art : $articolo Somma = a zero";
      return 0;
      exit;
  }

  if ($somma < $sottrarre){
      //echo "Art : $articolo Somma minore di sottrarre";
      return 0;
      exit;
  }

  while ($somma > 0){


        //echo "$somma meno $sottrarre";
        $somma = ($somma - $sottrarre);

        if($somma>=0){
            $scatole++;
        }

        //echo " UGUALE $somma , scatole = $scatole<br>";


  }
  return (int)$scatole;
}

function q_articoli_avanzo_articolo_ordine($ordine,$articolo){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
Sum(retegas_dettaglio_ordini.qta_ord),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
AND
retegas_dettaglio_ordini.id_articoli =  '$articolo'
GROUP BY
retegas_dettaglio_ordini.id_articoli
LIMIT 1";

  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);

  $scatole=0;

  (float)$somma = $row[0];
  (float)$sottrarre = $row[4];

  //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

  if (empty($somma)){
      //ho "Art : $articolo Somma = a zero";
      return 0;
      exit;
  }

  if ($somma < $sottrarre){
      //echo "Art : $articolo Somma minore di sottrarre";
      return $somma;
      exit;
  }

  while ($somma >= $sottrarre){


        //echo "$somma meno $sottrarre";
        $somma = round(($somma - $sottrarre),3);


        //echo " UGUALE $somma , avanzo = $somma<br>";


  }
  return $somma;
}
function q_articoli_avanzo_articolo_ordine_arr($ordine,$articolo){
  //Quanti articoli avanza l'ordine intero

  $ordine = intval($ordine);

  $sql = "SELECT
Sum(retegas_dettaglio_ordini.qta_arr),
retegas_articoli.codice,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.qta_minima,
retegas_articoli.qta_scatola
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$ordine'
AND
retegas_dettaglio_ordini.id_articoli =  '$articolo'
GROUP BY
retegas_dettaglio_ordini.id_articoli
LIMIT 1";

  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);

  $scatole=0;

  (float)$somma = $row[0];
  (float)$sottrarre = $row[4];

  //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

  if (empty($somma)){
      //echo "Art : $articolo Somma = a zero";
      return 0;
      exit;
  }

  if ($somma < $sottrarre){
      //echo "Art : $articolo Somma minore di sottrarre";
      return $somma;
      exit;
  }

  while ($somma >= $sottrarre){


        //echo "$somma meno $sottrarre";
        $somma = ($somma - $sottrarre);


        //echo " UGUALE $somma , avanzo = $somma<br>";


  }
  return $somma;
}
function q_articoli_avanzo_articolo_singolo($scatola,$q){
  $scatole=0;

  (float)$somma = $q;
  (float)$sottrarre = $scatola;

  //Echo "$row[0], $row[1], $row[2], $row[3], $row[4], Somma=$somma sottrarre=$sottrarre <br>";

  if (empty($somma)){
      //echo "Art : $articolo Somma = a zero";
      return 0;
      exit;
  }

  if ($somma < $sottrarre){
      //echo "Art : $articolo Somma minore di sottrarre";
      return $somma;
      exit;
  }



  while ($somma >= $sottrarre){


        //echo "$somma meno $sottrarre";
        $somma = ($somma - $sottrarre);


        //echo " UGUALE $somma , avanzo = $somma<br>";


  }
  return $somma;
}

function articoli_per_amici($id_riga_spesa,$id_amico){
  //ID Ordine --> ID referente GAS(id user)
  $sql = "SELECT
retegas_distribuzione_spesa.qta_arr
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_riga_dettaglio_ordine =  '$id_riga_spesa' AND
retegas_distribuzione_spesa.id_amico =  '$id_amico'";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];

}
function n_articoli_per_riga($id_riga){
  //ID Ordine --> ID referente GAS(id user)
  $sql = "SELECT
retegas_dettaglio_ordini.qta_arr
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_dettaglio_ordini =  '$id_riga'";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  $articoli_utente= $row[0];


$sql ="SELECT
Sum(retegas_distribuzione_spesa.qta_arr)
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_riga_dettaglio_ordine =  '$id_riga'";
   $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  $articoli_amici= $row[0];

return $articoli_utente;

}



function n_riga_ordini_from_code($id_ordine,$id_articolo,$id_user,$code){
global $db;
$sql="SELECT
retegas_dettaglio_ordini.id_dettaglio_ordini
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_articoli = '$id_articolo' AND
retegas_dettaglio_ordini.id_ordine = '$id_ordine' AND
retegas_dettaglio_ordini.id_Stati = '$code' AND
retegas_dettaglio_ordini.id_utenti = '$id_user' LIMIT 1;";
$ret =  $db->sql_query($sql);
$row =  $db->sql_fetchrow($ret);

return $row[0];


}
function n_riga_ordini_dettaglio_distribuzione($id_ordine,$id_articolo,$id_user){
global $db;
$sql="SELECT
retegas_dettaglio_ordini.id_dettaglio_ordini
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_articoli = '$id_articolo' AND
retegas_dettaglio_ordini.id_ordine = '$id_ordine' AND
retegas_dettaglio_ordini.id_utenti = '$id_user' LIMIT 1;";
$ret =  $db->sql_query($sql);
$row =  $db->sql_fetchrow($ret);

return $row[0];


}
function n_articoli_ordini_dettaglio_distribuzione_ord($ordine,$id_articolo,$id_amico,$id_user,$id_dett=null){


    //Echo "ordine $ordine, Articolo $id_articolo, Amico $id_amico, User $id_user, IDDett $id_dett<br>";


if(empty($id_dett)){
$sql="SELECT
retegas_distribuzione_spesa.qta_ord
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_amico =  '$id_amico' AND
retegas_distribuzione_spesa.id_ordine =  '$ordine' AND
retegas_distribuzione_spesa.id_articoli =  '$id_articolo' AND
retegas_distribuzione_spesa.id_user =  '$id_user'
LIMIT 1";
}else{
$sql="SELECT
retegas_distribuzione_spesa.qta_ord
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_amico =  '$id_amico' AND
retegas_distribuzione_spesa.id_riga_dettaglio_ordine = '$id_dett'
LIMIT 1";
}
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  //Echo "Risultato: ".$row[0]."<br>";
  return $row[0];


}
function lista_assegnatari_articolo_dettaglio($dettaglio_ordine){

global $db;

$sql="SELECT
retegas_amici.nome
FROM
retegas_distribuzione_spesa
Left Join retegas_amici ON retegas_distribuzione_spesa.id_amico = retegas_amici.id_amici
WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine = '$dettaglio_ordine'";
$ret = $db->sql_query($sql);
$lista ="<ul>";

while ($row = mysql_fetch_array($ret)){

    if(is_empty($row[0])){
        $lista .= "<li>Me stesso</li>";

    }else{
        $lista .= "<li>".$row[0]."</li>";

    }
}
$lista .="</ul>";
return $lista;

}

//CANCELLO ARTICOLO DA UN ORDINE DI USER
function do_delete_all_articolo_specfico($id_arti,$id_ordine,$id_user){
global $RG_addr;
global $db,$user;


// CANCELLO DALLA TABELLA DETTAGLI ORDINE.
$pippo = "delete from retegas_dettaglio_ordini WHERE retegas_dettaglio_ordini.id_articoli='$id_arti'    AND retegas_dettaglio_ordini.id_ordine='$id_ordine'    AND retegas_dettaglio_ordini.id_utenti='$id_user';";


//echo "SQL = ".$pippo."<br>";
$db->sql_query($pippo);
$quanti_dettagli = $db->sql_affectedrows();

// CANCELLO DALLA DISTRIBUZIONE
$sql2 = "delete from retegas_distribuzione_spesa
        WHERE
        retegas_distribuzione_spesa.id_articoli='$id_arti'
        AND retegas_distribuzione_spesa.id_ordine='$id_ordine'
        AND retegas_distribuzione_spesa.id_user='$id_user';";

$db->sql_query($sql2);
$quante_distribuzioni = $db->sql_affectedrows();

        $msg = "Cancellati tutti gli articoli cod. $id_arti dall'ordine e la loro distribuzione ($quanti_dettagli dettagli e $quante_distribuzioni distribuzioni)";
        $id = $id_ordine;


        $vo = valore_totale_ordine($id);
        $no = descrizione_ordine_from_id_ordine($id);

        if(_USER_USA_CASSA){
        // CANCELLO DALLA CASSA
            if(read_option_prenotazione_ordine($id,$id_user)<>"SI"){
                    $log .="PRENOTAZIONE ? NO, eseguo update cassa<br>";
                    cassa_update_ordine_utente($id,$id_user);
            }else{
                    $log .="PRENOTAZIONE ? SI, salto update cassa<br>";
            }
        }else{
            $log .="USER USA CASSA ? NO, salto update cassa<br>";
        }
        log_me($id,$id_user,"ORD","ART","Eliminazione multipla di articoli all'ordine $id ($no), adesso vale $vo",$vo,$msg."<br>".$pippo."---".$sql2."---<br>".$log);



}
function do_delete_all_ordine_user($id_ordine,$id_user){
global $db;
// CANCELLO DALLA TABELLA DETTAGLI ORDINE.
$pippo = "delete from retegas_dettaglio_ordini
            WHERE retegas_dettaglio_ordini.id_ordine='$id_ordine'
            AND retegas_dettaglio_ordini.id_utenti='$id_user';";

$db->sql_query($pippo);


// CANCELLO DALLA DISTRIBUZIONE
$sql2 = "delete from retegas_distribuzione_spesa
        WHERE
        retegas_distribuzione_spesa.id_ordine='$id_ordine'
        AND retegas_distribuzione_spesa.id_user='$id_user';";

$db->sql_query($sql2);

// CANCELLO DALLA CASSA
$sql3 = "delete from retegas_cassa_utenti
        WHERE
        retegas_cassa_utenti.id_ordine='$id_ordine'
        AND retegas_cassa_utenti.id_utente='$id_user';";

$db->sql_query($sql3);


log_me($id_ordine,$id_user,"ORD","DEL","Eliminazione totale ordine $id_ordine",0,$pippo."<br>".$sql2."<br>".$sql3);


}

function testo_maggiorazione_mio_gas($ordine,$gas){
    //echo $ordine." - ".$gas;
    global $db;
    $query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
    $result = $db->sql_query($query);
    $row = $db->sql_fetchrow($result);

    return $row["note_referenza"];
}

function luogo_distribuzione_mio_gas($ordine,$gas){
    //echo $ordine." - ".$gas;
    global $db;
    $query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
    $result = $db->sql_query($query);
    $row = $db->sql_fetchrow($result);

    return $row["luogo_distribuzione"];
}
function testo_distribuzione_mio_gas($ordine,$gas){
    //echo $ordine." - ".$gas;
    global $db;
    $query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
    $result = $db->sql_query($query);
    $row = $db->sql_fetchrow($result);

    return $row["testo_distribuzione"];
}
function data_distribuzione_start_mio_gas($ordine,$gas){
    //echo $ordine." - ".$gas;
    global $db;
    $query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
    $result = $db->sql_query($query);
    $row = $db->sql_fetchrow($result);

    return $row["data_distribuzione_start"];
}
function data_distribuzione_end_mio_gas($ordine,$gas){
    //echo $ordine." - ".$gas;
    global $db;
    $query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
    $result = $db->sql_query($query);
    $row = $db->sql_fetchrow($result);

    return $row["data_distribuzione_end"];
}


function fullname_referente_ordine_proprio_gas($ordine,$gas){
  //ID articolo --> IdUser
  $sql = "SELECT maaking_users.fullname
FROM ((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid
WHERE (((retegas_ordini.id_ordini)='$ordine') AND ((retegas_referenze.id_gas_referenze)='$gas'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}

function mail_referente_ordine_proprio_gas($ordine,$gas){
  //ID articolo --> IdUser
  $sql = "SELECT maaking_users.email
FROM ((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid
WHERE (((retegas_ordini.id_ordini)='$ordine') AND ((retegas_referenze.id_gas_referenze)='$gas'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function tel_referente_ordine_proprio_gas($ordine,$gas){
  //ID articolo --> IdUser
  $sql = "SELECT maaking_users.tel
FROM ((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid
WHERE (((retegas_ordini.id_ordini)='$ordine') AND ((retegas_referenze.id_gas_referenze)='$gas'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function mail_referente_ordine_globale($ordine){


$sql = "SELECT
maaking_users.email
FROM
retegas_ordini
Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
WHERE
retegas_ordini.id_ordini =  '$ordine'";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];


}
function fullname_referente_ordine_globale($ordine){


$sql = "SELECT
maaking_users.fullname
FROM
retegas_ordini
Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
WHERE
retegas_ordini.id_ordini =  '$ordine'";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];


}


function id_referente_ordine_proprio_gas($ordine,$gas){
  //ID Ordine --> ID referente GAS(id user)
  $sql = "SELECT maaking_users.userid
FROM ((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid
WHERE (((retegas_ordini.id_ordini)='$ordine') AND ((retegas_referenze.id_gas_referenze)='$gas'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function id_referente_ordine_globale($ordine){
  //ID Ordine --> ID referente GAS(id user)
  $sql = "SELECT retegas_ordini.id_utente FROM retegas_ordini WHERE retegas_ordini.id_ordini = '$ordine' LIMIT 1;";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function id_user_from_id_dettaglio_ordine($id_dett){
  $sql = "SELECT retegas_dettaglio_ordini.id_utenti FROM retegas_dettaglio_ordini WHERE retegas_dettaglio_ordini.id_dettaglio_ordini = '$id_dett' LIMIT 1;";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
Function descrizione_ordine_from_id_ordine($idu){
 // ID ordine ----> ID listino

$sql = "SELECT retegas_ordini.descrizione_ordini FROM retegas_ordini
WHERE (((retegas_ordini.id_ordini)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);

  return $row[0];
}
Function avanzamento_ordine_from_id_ordine($idu){
 // ID ordine ----> ID listino

$sql = "SELECT

(DATEDIFF(NOW(),DATE(retegas_ordini.data_apertura)) / DATEDIFF(DATE(retegas_ordini.data_chiusura),DATE(retegas_ordini.data_apertura))*100)
FROM
retegas_ordini
WHERE
retegas_ordini.id_ordini =  '$idu';";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];

}

Function listino_ordine_from_id_ordine($idu){
global $db;

$sql = "SELECT retegas_ordini.id_listini FROM retegas_ordini
WHERE (((retegas_ordini.id_ordini)='$idu'));";
  $ret = $db->sql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}

Function elenco_mail_ordine($ord){

 // ID ordine ---> Recordset delle mail
 $sql ="SELECT maaking_users.email
FROM retegas_ordini INNER JOIN (retegas_dettaglio_ordini INNER JOIN maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid) ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
GROUP BY retegas_ordini.id_ordini, maaking_users.email
HAVING (((retegas_ordini.id_ordini)=$ord));";
  $ret = mysql_query($sql);
  return $ret;

}
Function elenco_fullname_ordine($ord){

 // ID ordine ---> Recordset dei fullname
 $sql ="SELECT maaking_users.fullname
FROM retegas_ordini INNER JOIN (retegas_dettaglio_ordini INNER JOIN maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid) ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
GROUP BY retegas_ordini.id_ordini, maaking_users.email
HAVING (((retegas_ordini.id_ordini)=$ord));";
  $ret = mysql_query($sql);
  return $ret;

}


Function ordine_bacino_utenti($ord){
$sql="SELECT
Count(maaking_users.username)
FROM
retegas_referenze
Left Join maaking_users ON retegas_referenze.id_gas_referenze = maaking_users.id_gas
WHERE
retegas_referenze.id_ordine_referenze =  '$ord'
AND maaking_users.isactive=1;";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
Function ordine_bacino_utenti_part_gas($ord,$gas){
$sql="SELECT
count(maaking_users.username)
FROM
retegas_dettaglio_ordini
Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
WHERE
maaking_users.isactive='1' AND
retegas_dettaglio_ordini.id_ordine =  '$ord' AND
maaking_users.id_gas =  '$gas'

GROUP BY
maaking_users.userid";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);

  return $row;
}

//PRENOTAZIONI
function ordine_valore_parte_prenotata($id_ordine){
    global $db, $RG_addr;

    $sql = "SELECT
            Sum(retegas_dettaglio_ordini.qta_ord * retegas_articoli.prezzo) AS somma,
            retegas_dettaglio_ordini.id_utenti
            FROM
            retegas_dettaglio_ordini
            Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
            WHERE
            retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
            AND
                (SELECT retegas_options.chiave FROM retegas_options
                WHERE retegas_options.id_user = retegas_dettaglio_ordini.id_utenti
                AND retegas_options.id_ordine = retegas_dettaglio_ordini.id_ordine
                AND retegas_options.chiave ='PRENOTAZIONE_ORDINI') is not null

            GROUP BY
            retegas_dettaglio_ordini.id_utenti";
    $res = $db->sql_query($sql);

    $t=0;
    while ($row = $db->sql_fetchrow($res)){
        $t = $t + $row["somma"];
    }

    return round($t,4);

}
function ordine_valore_parte_prenotata_user($id_ordine,$id_utente){
    global $db, $RG_addr;

    $sql = "SELECT
            Sum(retegas_dettaglio_ordini.qta_ord * retegas_articoli.prezzo) AS somma,
            retegas_dettaglio_ordini.id_utenti
            FROM
            retegas_dettaglio_ordini
            Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
            WHERE
            retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
            AND
                (SELECT retegas_options.chiave FROM retegas_options
                WHERE retegas_options.id_user = retegas_dettaglio_ordini.id_utenti
                AND retegas_options.id_ordine = retegas_dettaglio_ordini.id_ordine
                AND retegas_options.chiave ='PRENOTAZIONE_ORDINI') is not null
            AND retegas_dettaglio_ordini.id_utenti = '$id_utente'
            GROUP BY
            retegas_dettaglio_ordini.id_utenti";
    $res = $db->sql_query($sql);

    $t=0;
    while ($row = $db->sql_fetchrow($res)){
        $t = $t + $row["somma"];
    }

    return round($t,4);

}
function ordine_valore_parte_confermata($id_ordine){
    return round(valore_totale_ordine_qarr($id_ordine)-ordine_valore_parte_prenotata($id_ordine),4);
}
function ordine_valore_parte_confermata_user($id_ordine,$id_utente){
    //NON SERVE IN QUANTO SE ESISTE LA PRENOTAZIONE QUESTA E' SEMPRE A 0
    return round(valore_arrivato_netto_ordine_user($id_ordine,$id_utente)-ordine_valore_parte_prenotata_user($id_ordine,$id_utente),4);
}

function n_ordini_partecipabili($gas){
    global $db;
    $my_query="SELECT retegas_ordini.id_ordini,
            retegas_ordini.descrizione_ordini,
            retegas_listini.descrizione_listini,
            retegas_ditte.descrizione_ditte,
            retegas_ordini.data_chiusura,
            retegas_gas.descrizione_gas,
            retegas_referenze.id_gas_referenze,
            maaking_users.userid,
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE (((retegas_ordini.data_chiusura)>NOW()) AND ((retegas_ordini.data_apertura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
    $res = $db->sql_query($my_query);
    return $db->sql_numrows($res);

}
function n_ordini_futuri($gas){
    global $db;
 $my_query="SELECT retegas_ordini.id_ordini,
            retegas_ordini.descrizione_ordini,
            retegas_listini.descrizione_listini,
            retegas_ditte.descrizione_ditte,
            retegas_ordini.data_chiusura,
            retegas_gas.descrizione_gas,
            retegas_referenze.id_gas_referenze,
            maaking_users.userid,
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE (((retegas_ordini.data_apertura)>NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
    $res = $db->sql_query($my_query);
    return $db->sql_numrows($res);

}
function n_ordini_chiusi($gas){
    global $db;
$my_query = "SELECT retegas_ordini.id_ordini,
            retegas_ordini.descrizione_ordini,
            retegas_listini.descrizione_listini,
            retegas_ditte.descrizione_ditte,
            retegas_ordini.data_chiusura,
            retegas_gas.descrizione_gas,
            retegas_referenze.id_gas_referenze,
            maaking_users.userid,
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE (((retegas_ordini.data_chiusura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura DESC;";
    $res = $db->sql_query($my_query);
    return $db->sql_numrows($res);

}
function n_ordini_non_confermati($id_user,$giorni){
global $db;
$sql = "SELECT * FROM retegas_ordini WHERE id_utente='$id_user' AND is_printable=0 AND (DATE_ADD(data_chiusura, INTERVAL $giorni DAY) < NOW());";
return $db->sql_numrows($db->sql_query($sql));

}

//DES
function n_gestori_ordini($gas,$minimo=1){
  global $db;
              $sql = "SELECT
            Count(retegas_ordini.id_ordini) AS ordini_gestiti
            FROM
            maaking_users
            Inner Join retegas_ordini ON maaking_users.userid = retegas_ordini.id_utente
            WHERE
            maaking_users.id_gas =  '$gas'
            GROUP BY
            maaking_users.userid
            HAVING
            ordini_gestiti >=  '$minimo'";
return (int)$db->sql_numrows($db->sql_query($sql));

}
function n_partecipanti_ordini($gas,$minimo=1){
  global $db;
              $sql = "SELECT
            Count(retegas_ordini.id_ordini) AS ordini_gestiti
            FROM
            maaking_users
            Inner Join retegas_ordini ON maaking_users.userid = retegas_ordini.id_utente
            WHERE
            maaking_users.id_gas =  '$gas'
            GROUP BY
            maaking_users.userid
            HAVING
            ordini_gestiti >=  '$minimo'";
return (int)$db->sql_numrows($db->sql_query($sql));

}
function n_ordini_condivisi_gas($id_gas){
    Global $db;
    $sql = "SELECT
                retegas_referenze.id_referenze,
                retegas_referenze.id_utente_referenze
                FROM
                retegas_referenze
                WHERE
                retegas_referenze.id_gas_referenze =  '$id_gas' AND
                retegas_referenze.id_utente_referenze <>  '0'";
    $res = $db->sql_query($sql);
    $row = $db->sql_numrows($res);

    return $row;
}
function n_ordini_gestiti_gas($id_gas){
    Global $db;
    $sql = "SELECT
            retegas_ordini.id_ordini
            FROM
            retegas_ordini
            Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
            WHERE
            maaking_users.id_gas =  '$id_gas'";
    $res = $db->sql_query($sql);
    $row = $db->sql_numrows($res);

    return $row;
}

//RETTIFICHE
function ridistribuisci_quantita_amici_1($key,$nq,&$msg){
    //echo "---- Ridistribuisco $key con $nq <br>";
    global $db;
// Ho la lista degli amici riferita all'articolo KEY
$qry ="SELECT
retegas_distribuzione_spesa.id_distribuzione,
retegas_distribuzione_spesa.id_riga_dettaglio_ordine,
retegas_distribuzione_spesa.qta_ord,
retegas_distribuzione_spesa.qta_arr,
retegas_distribuzione_spesa.id_amico
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_riga_dettaglio_ordine =  '$key'
ORDER BY
retegas_distribuzione_spesa.id_amico DESC";


// Adesso la popolo con la nuova quantità partendo dall'ultima riga immessa;
// in realtà cancellando e ripopolando tutto ho sempre lo stesso utente penalizzato;

    $result = $db->sql_query($qry);
    $totalrows = $db->sql_numrows($result);
    $rimasto = $nq;
    $i = 0;
    while ($row = mysql_fetch_array($result)){

        $i++;
        $l .= "------------->Ciclo n.$i<br>";

        $a = $rimasto - $row['qta_ord'];
        $id_q = $row['id_distribuzione'];

        if($a>0){
            $l .= "------------->Rimasto - Qord > 0 <br>";
            $q_a = $row['qta_ord'];
            $rimasto=$a;

            // se è l'ultima riga allora aggiungo un po' di roba
            if($i==$totalrows){

                 $q_a = $rimasto + $row['qta_ord'];
                 $rimasto=0;
                 $l .= "------------->Ultima riga; qa= (rimasto + qord) $q_a <br>";
            }

        }else{

            $l .= "------------->Rimasto - Qord = 0 <br>";
            $q_a = $rimasto;
            $rimasto=0;
        }


    $l .= "------------->INSERISCO $q_a in $id_q<br>";
    // update
    $result2 = mysql_query("UPDATE retegas_distribuzione_spesa
                            SET retegas_distribuzione_spesa.qta_arr = '$q_a',
                                retegas_distribuzione_spesa.data_ins = NOW()
                            WHERE (retegas_distribuzione_spesa.id_distribuzione='$id_q');");

     $l .= "------------->Fine riga<br><br>";

    // CICLO DI UPDATE
    }
    log_me(0,_USER_ID,"DIS","1","ridistribuisci_quantita_amici_1",0,$l);


}
