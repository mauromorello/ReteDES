<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     pussa_via();
}

if(!isset($id_ordine)){
     pussa_via();
}

if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
    go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
    exit;
}

if(stato_from_id_ord($id_ordine)<>3){
       go("ordini_form",_USER_ID,"Questa ordine non è validabile.","?id_ordine=$id_ordine");

    die();
}

if ($do=="do_allow_print"){
    $sql = "UPDATE `my_retegas`.`retegas_ordini` SET `is_printable` = '1' WHERE `retegas_ordini`.`id_ordini` = '$id_ordine' LIMIT 1;";
    $db->sql_query($sql);
    log_me($id_ordine,_USER_ID,"ORD","MOD","Convalidato ordine $id_ordine, ",0);

//CANCELLO LE REFERENZE DI QUESTO ORDINE DEI GAS SENZA REFERENTE

//    $sql_ref = "DELETE FROM retegas_referenze WHERE id_ordine_referenze='$id_ordine' AND id_utente_referenze=0;";
//    $db->sql_query($sql_ref);
//    log_me($id_ordine,_USER_ID,"REF","MOD","Cancellate referenze non usate ",0,$sql_ref);

if(CAST_TO_INT($opera_su_cassa,0)>0){

    //----------------------------------------------------------DA INSERIRE PER SCARICO AUTOMATICO
        // SE IL GAS PERMETTE LO SCARICO IN AUTOMATICO
        if(read_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_SCARICO_AUTOMATICO")=="SI"){

            if(_USER_PERMISSIONS & perm::puo_operare_con_crediti){
                if(cassa_update_ordine_totale($id_ordine,"si")=="OK"){
                    $msg_cassa .= "Cassa Correttamente Aggiornata<br>
                                   Utente con i permessi per operare con crediti utente;<br>
                                   Movimenti REGISTRATI<br>";
                }else{
                    $msg_cassa .= "Cassa NON AGGIORNATA !!!<br>";
                };
            }else{
                if(cassa_update_ordine_totale($id_ordine,"no")=="OK"){
                    $msg_cassa .= "Cassa Correttamente Aggiornata<br>
                                   Utente SENZA i permessi per operare con crediti utente;<br>
                                   Movimenti NON REGISTRATI<br> ";
                }else{
                    $msg_cassa .= "Cassa NON AGGIORNATA !!!<br>";
                };
            }

    //        //MAIL AI CASSIERI DEL GAS !!!!
           $messaggio = "<p>E' stato convalidato l'ordine $id_ordine, dall'utente "._USER_FULLNAME.";</p>
                         <p>Il tuo gas ha attiva l'opzione di scarico credito in automatico</p>
                         <p>il risultato è : <br><i>$msg_cassa</i></p>
                         ";
           mail_ai_cassieri(_USER_ID_GAS,"["._SITE_NAME." - Scarico credito automatico] - Ordine $id_ordine",$messaggio);


        }
    //------------------------------------------------------------------------------
    }

    $msg = "Ordine Convalidato con successo; Una mail è stata inviata ai cassieri.<br>".$msg_cassa;
    go("ordini_form_new",_USER_ID,$msg,"?id_ordine=$id_ordine");
    die();


}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Conferma convalida ordine";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}

if(read_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_SCARICO_AUTOMATICO")=="SI"){

    if(_USER_PERMISSIONS & perm::puo_operare_con_crediti){

        $a.="<p>Il tuo gas permette i movimenti su cassa direttamente dalla convalida ordine.</p>";
        $a.="<p>Se sei abilitato ad operare con i crediti degli utenti i movimenti saranno subito contabilizzati, in caso contrario dovranno passare dal cassiere per una verifica.;</p>";
        $a.="<p>Spuntando questo check all'atto della convalida ordine saranno scalati gli importi corrispondenti dalla cassa degli utenti partecipanti</p>";
        $a.='<input type="checkbox" name="opera_su_cassa" value="1"> Scala i valori dell\'ordine dai crediti degli utenti.<br>';

        $aggiorna_la_cassa = $a;
    }
}


//Contenuto
$h .= " <div class=\"rg_widget rg_widget_helper\">
        <div>
        <h3>ATTENZIONE</h3>
        <p>L'operazione di convalida non è reversibile ! prima di convalidare controllate sempre che tutti gli importi siano corretti.</p>
        </div>
            <form method=\"POST\" class=\"retegas_form\" action=\"\">
                <h3>Cliccando su \"convalida\" Questo ordine sarà chiuso definitivamente, e tutti gli utenti potranno ufficialmente visionare e stampare i loro totali.</h3>
                $aggiorna_la_cassa
            <input type=\"hidden\" name=\"do\" value=\"do_allow_print\">
            <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
            <input class =\"large green awesome\" style=\"margin:20px;\" type=\"submit\" value=\"Convalida\">
            oppure <a class =\"large red awesome\" style=\"margin:20px;\" href=\"".$RG_addr["ordini_form_new"]."?id_ordine=$id_ordine\"><strong>Abbandona</strong></a>
            </form>
             <br>


        </div>";

//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);