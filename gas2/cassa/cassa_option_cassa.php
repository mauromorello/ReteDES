<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

if (!(_USER_PERMISSIONS & perm::puo_gestire_la_cassa)){
    pussa_via();
}




if($do=="do_gas_copertura_cassa"){
    $gas_copertura_cassa =  CAST_TO_INT($gas_copertura_cassa,0,100);

    write_option_gas_text(_USER_ID_GAS,"_GAS_COPERTURA_CASSA",$gas_copertura_cassa);
    sleep(1);
    $msg="Impostazione modificata";
    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_COPERTURA_CASSA : $gas_copertura_cassa",_USER_ID_GAS,null);

}

if($do=="do_gas_cassa_min_level"){
    //POSSIBILITA' DI NEGATIVO
    $gas_cassa_min_level =  CAST_TO_FLOAT($gas_cassa_min_level,-1000,1000);

    write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_MIN_LEVEL",$gas_cassa_min_level);
    sleep(1);
    $msg="Impostazione modificata";
    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_CASSA_MIN_LEVEL : $gas_cassa_min_level",_USER_ID_GAS,null);

}

if($do=="do_gas_cassa_check_min_level"){
    $gas_cassa_check_min_level =  CAST_TO_STRING($gas_cassa_check_min_level,2);
    if($gas_cassa_check_min_level=="SI"){
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_CHECK_MIN_LEVEL","SI");
    }else{
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_CHECK_MIN_LEVEL","NO");
    }
    sleep(1);
    $msg="Impostazione modificata";
    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_CASSA_CHECK_MIN_LEVEL : $gas_cassa_check_min_level",_USER_ID_GAS,null);

}

if($do=="do_gas_cassa_use_password_confirm"){
    $gas_cassa_use_password_confirm =  CAST_TO_STRING($gas_cassa_use_password_confirm,2);
    if($gas_cassa_use_password_confirm=="SI"){
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_USE_PASSWORD_CONFIRM","SI");
    }else{
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_USE_PASSWORD_CONFIRM","NO");
    }
    sleep(1);
    $msg="Impostazione modificata";
    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_CASSA_USE_PASSWORD_CONFIRM : $gas_cassa_use_password_confirm",_USER_ID_GAS,null);

}

if($do=="do_gas_cassa_prenotazione_ordini"){
    $gas_cassa_prenotazione_ordini =  CAST_TO_STRING($gas_cassa_prenotazione_ordini,2);
    if($gas_cassa_prenotazione_ordini=="SI"){
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_PRENOTAZIONE_ORDINI","SI");
        $msg="PRENOTAZIONI : <strong>SI</strong>";
    }else{
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_PRENOTAZIONE_ORDINI","NO");
         $msg="PRENOTAZIONI : <strong>NO</strong>";
    }
    sleep(1);

    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_CASSA_PRENOTAZIONE_ORDINI : $gas_cassa_prenotazione_ordini",_USER_ID_GAS,null);

}

if($do=="do_gas_cassa_default_solo_cassati"){
    $gas_cassa_default_solo_cassati =  CAST_TO_STRING($gas_cassa_default_solo_cassati,2);
    if($gas_cassa_default_solo_cassati=="SI"){
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_DEFAULT_SOLO_CASSATI","SI");
        $msg="Default SOLO CHI HA LA CASSA : <strong>SI</strong>";
    }else{
        write_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_DEFAULT_SOLO_CASSATI","NO");
         $msg="Default SOLO CHI HA LA CASSA : <strong>NO</strong>";
    }
    sleep(1);

    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_CASSA_DEFAULT_SOLO_CASSATI : $gas_cassa_default_solo_cassati",_USER_ID_GAS,null);

}

if($do=="do_gas_cassa_scarico_automatico"){
    $gas_cassa_scarico_automatico =  CAST_TO_STRING($gas_cassa_scarico_automatico,2);
    if($gas_cassa_scarico_automatico=="SI"){
        write_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_SCARICO_AUTOMATICO","SI");
        $msg="Scarico automatico : <strong>SI</strong>";
    }else{
        write_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_SCARICO_AUTOMATICO","NO");
         $msg="Scarico automatico : <strong>NO</strong>";
    }
    sleep(1);

    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_CASSA_SCARICO_AUTOMATICO : $gas_cassa_scarico_automatico",_USER_ID_GAS,null);

}

if($do=="do_gas_cassa_visualizzazione_saldo"){
    $gas_cassa_visualizzazione_saldo =  CAST_TO_STRING($gas_cassa_visualizzazione_saldo,1);
    if($gas_cassa_visualizzazione_saldo=="1"){
        write_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_VISUALIZZAZIONE_SALDO","1");
        $msg="VISUALIZZAZIONE SALDO : <strong>Tipo 1</strong>";
    }else{
        write_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_VISUALIZZAZIONE_SALDO","2");
         $msg="VISUALIZZAZIONE SALDO : <strong>Tipo 2</strong>";
    }
    sleep(1);

    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_CASSA_VISUALIZZAZIONE_SALDO : $gas_cassa_visualizzazione_saldo",_USER_ID_GAS,null);

}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opzioni Cassa";


//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale[] = gas_menu_gestisci_cassa();

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//--------------------------------------------CONTENUTO











//Uso cassa
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Gestione cassa</h2>


        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Percentuale per copertura cassa</h3>
        <label for=\"gas_copertura_cassa\">Percentuale che in automatico viene aggiunta all'importo di un ordine per garantire la copertura della cassa, a fronte di spese di trasporto e di gestione non confermate.</label>
        <input type=\"number\" id=\"gas_copertura_cassa\"  name=\"gas_copertura_cassa\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_COPERTURA_CASSA")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_copertura_cassa\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Controllo minimo di cassa</h3>
        <label for=\"gas_cassa_check_min_level\">Controlla che gli utenti non possano scendere sotto il minimo di cassa quando fanno gli ordini (SI/NO)</label>
        <input id=\"gas_cassa_check_min_level\"  name=\"gas_cassa_check_min_level\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_CHECK_MIN_LEVEL")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa_check_min_level\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>


        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Livello minimo di cassa</h3>
        <label for=\"gas_cassa_min_level\">Somma minima che ogni utente deve avere sul suo conto. Se scende sotto questa soglia non è possibile per lui ordinare merce.</label>
        <input type=\"number\" id=\"gas_cassa_min_level\"  name=\"gas_cassa_min_level\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_MIN_LEVEL")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa_min_level\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong>
        <p>Questa soglia è considerata solo se nell'opzione \"Controllo minimo di cassa \" è stato settato \"SI\"</p>
        </div>


        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Conferma password</h3>
        <label for=\"gas_cassa_use_password_confirm\">Per ogni operazione importante viene richiesta la password del cassiere che la effettua. (SI/NO)</label>
        <input id=\"gas_cassa_use_password_confirm\"  name=\"gas_cassa_use_password_confirm\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_USE_PASSWORD_CONFIRM")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa_use_password_confirm\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Permetti prenotazione ordini</h3>
        <label for=\"gas_cassa_prenotazione_ordini\">Permetti che gli utenti possano ordinare merce SENZA intaccare il loro credito. (vedi istruzioni su wiki.retedes.it) (SI/NO)</label>
        <input id=\"gas_cassa_prenotazione_ordini\"  name=\"gas_cassa_prenotazione_ordini\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_PRENOTAZIONE_ORDINI")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa_prenotazione_ordini\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Default nuovi ordini SOLO PER CHI HA LA CASSA</h3>
        <label for=\"gas_cassa_default_solo_cassati\">Quando parte un ordine semplice, se scegli SI parte in automatico riservato solo a chi ha la cassa. (SI/NO)</label>
        <input id=\"gas_cassa_default_solo_cassati\"  name=\"gas_cassa_default_solo_cassati\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_DEFAULT_SOLO_CASSATI")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa_default_solo_cassati\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Scarico credito automatizzato alla chiusura</h3>
        <label for=\"gas_cassa_scarico_automatico\">Alla CONVALIDA dell'ordine da parte del suo gestore, tutti i movimenti della cassa saranno allineati con qualli dell'ordine. (SI/NO) - NB: Se l'utente gestore
        ha i permessi \"permetti movimenti crediti utenti\" i movimenti scaricati saranno già impostati come CONTABILIZZATI, saltando il passaggio del cassiere.</label>
        <input id=\"gas_cassa_scarico_automatico\"  name=\"gas_cassa_scarico_automatico\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_SCARICO_AUTOMATICO")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa_scarico_automatico\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Visualizzazione saldo</h3>
        <label for=\"gas_cassa_visualizzazione_saldo\">inserire \"1\" per visualizzare il saldo come credito residuo effettivo (totale - ancora da confermare), mentre \"2\" includendo anche i movimenti da confermare.</label>
        <input id=\"gas_cassa_visualizzazione_saldo\"  name=\"gas_cassa_visualizzazione_saldo\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_VISUALIZZAZIONE_SALDO")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa_visualizzazione_saldo\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>


      </div>";


//-----------------------------------------------------


//$r->contenuto = rg_toggable("Alcune novità","poio",$bla,false).$h;
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r)

?>