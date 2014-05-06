<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

if($do=="do_site_logo"){
    write_option_gas_text(_USER_ID_GAS,"_GAS_SITE_LOGO",sanitize($site_logo));
    sleep(1);
    $msg="Logo modificato correttamente";
}
if($do=="do_site_inattivita"){
    $max = read_option_max_giorni_gas(_USER_ID_GAS);
    if($max==0){$max=999999;}

    write_option_gas_text(_USER_ID_GAS,"_GAS_SITE_INATTIVITA",CAST_TO_INT($site_inattivita,0,$max));
    sleep(1);
    $msg="Giorni inattività modificati correttamente";
}
if($do=="do_site_frase_inattivita"){
    write_option_gas_text(_USER_ID_GAS,"_GAS_SITE_FRASE_INATTIVITA",sanitize($site_frase_inattivita));
    sleep(1);
    $msg="Frase inattività modificata correttamente";
}
if($do=="do_gas_cassa"){
    if(sanitize($gas_usa_cassa)<>"SI"){
        $gas_usa_cassa = "NO";

        //TOGLIE LA CASSA A TUTTI I SUOI UTENTI
        $sql = "SELECT * FROM maaking_users WHERE id_gas='"._USER_ID_GAS."'";
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)){
            write_option_text($row["userid"],"_USER_USA_CASSA","NO");
        }
    }
    //write_option_gas_text(_USER_ID_GAS,"_GAS_USA_CASSA",$gas_usa_cassa);
    write_option_gas_text_new(_USER_ID_GAS,"_GAS_USA_CASSA",$gas_usa_cassa);

    sleep(1);
    $msg="Impostazione CASSA modificata.";
    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_USA_CASSA : $gas_usa_cassa",_USER_ID_GAS,null);
}

if($do=="do_gas_puo_partecipare_ordini_esterni"){
    if(sanitize($gas_puo_partecipare_ordini_esterni)<>"SI"){
        $gas_puo_partecipare_ordini_esterni = "NO";
    }
    //write_option_gas_text(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST",$gas_puo_partecipare_ordini_esterni);
    write_option_gas_text_new(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST",$gas_puo_partecipare_ordini_esterni);

    sleep(1);
    $msg="Impostazione modificata";
}

if($do=="do_gas_visione_condivisa"){
    if(sanitize($gas_visione_condivisa)<>"SI"){
        $gas_visione_condivisa = "NO";
    }
    //write_option_gas_text(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST",$gas_puo_partecipare_ordini_esterni);
    write_option_gas_text_new(_USER_ID_GAS,"_GAS_VISIONE_CONDIVISA",$gas_visione_condivisa);

    sleep(1);
    $msg="Impostazione modificata";
}

if($do=="do_gas_condivisione_propri_ordini"){
    if(sanitize($gas_condivisione_propri_ordini)<>"SI"){
        $gas_condivisione_propri_ordini = "NO";
    }
    //write_option_gas_text(_USER_ID_GAS,"_GAS_PUO_COND_ORD_EST",$gas_condivisione_propri_ordini);
    write_option_gas_text_new(_USER_ID_GAS,"_GAS_PUO_COND_ORD_EST",$gas_condivisione_propri_ordini);

    sleep(1);
    $msg="Impostazione modificata";
}


//SHOW USERID
if($do=="do_site_show_userid"){
    if(sanitize($site_show_userid)<>"SI"){
        $site_show_userid = "NO";
    }
    write_option_gas_text(_USER_ID_GAS,"_SITE_SHOW_USERID",$site_show_userid);
    sleep(1);
    $msg="Impostazione modificata";
}

//HASHTAG TWEET
if($do=="do_hashtag_gas"){
    write_option_gas_text_new(_USER_ID_GAS,"_HASHTAG_GAS",sanitize(substr(trim($hashtag_gas),0,4)));
    sleep(1);
    $msg="Impostazione modificata";
}
//WPID
if($do=="do_wpid_gas"){
    write_option_gas_text_new(_USER_ID_GAS,"_WPID_GAS",sanitize(substr(trim($wpid_gas),0,10)));
    sleep(1);
    $msg="Impostazione modificata";
}
if($do=="do_pulsante_partecipa"){
    write_option_gas_text_new(_USER_ID_GAS,"_PULSANTE_PARTECIPA",sanitize(substr(trim($pulsante_partecipa),0,50)));
    sleep(1);
    $msg="Impostazione modificata";
}

//REFERENTI MULTIPLI
if($do=="do_referenti_multipli"){
    if(sanitize($referenti_multipli)<>"SI"){
        $referenti_multipli = "NO";
    }
    write_option_gas_text_new(_USER_ID_GAS,"_REFERENTI_MULTIPLI",$referenti_multipli);
    sleep(1);
    $msg="Impostazione modificata";
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva =  menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opzioni GAS";


//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = gas_menu_completo($user);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//--------------------------------------------CONTENUTO



//LOGO
$h2 .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Logo del sito</h3>
        <label for=\"site_logo\">Il logo del proprio Gas</label>
        <input id=\"site_logo\" DISABLED name=\"site_logo\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_SITE_LOGO")."\" size=\"40\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_logo\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong>
        <p>Il logo verrà ridimensionato a 75x75 pixels</p>
        <p>Immettere l'URL del logo. Per il momento non è possibile caricare su retegas il file originale.</p>
        </div>

      </div>";

//GG inattività
$max_gg = read_option_max_giorni_gas(_USER_ID_GAS);
if($max_gg==0){$max_gg="";}else{$max_gg="(Max $max_gg Giorni, decisi dal tuo DES.)";}

$h .= "<div class=\"rg_widget rg_widget_helper\">
        <h2>Gestione sospensione utenti</h2>
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.nextme.it/images/stories/Rubriche/LoSapeviChe/pigrizia.jpg\" style=\"width:80px;height:80px\">

        <label for=\"site_inattivita\">Giorni di inattività per essere sospesi $max_gg</label>
        <input type=\"number\" id=\"site_inattivita\"  name=\"site_inattivita\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_SITE_INATTIVITA")."\" size=\"3\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_inattivita\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://avvertenze.aduc.it/generale/files/image/2010/gennaio/sgridata.png\" style=\"width:80px;height:80px\">

        <label for=\"site_frase_inattivita\">Frase che compare quando un utente sospeso in automatico si connette la prossima volta.</label>
        <input id=\"site_frase_inattivita\"  name=\"site_frase_inattivita\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_SITE_FRASE_INATTIVITA")."\" size=\"40\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_frase_inattivita\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong>
        <p>Il sito controlla giornalmente da quanto tempo ogni utente non ha più effettuato un accesso.</p>
        <p>Superato questo limite l'utente viene \"sospeso\", in modo da rendere necessario l'intervento del \"gestore utenti\" per riattivarlo.</p>
        <p>Se non viene impostata nessuna frase di avviso, verrà proposta quella standard \"Account sospeso per prolungata inattività\"; La frase di avviso è impostabile singolarmente dalla scheda
        di ogni utente. Dalla stessa scheda è possibile riattivarlo o cancellarlo definitivamente.</p>
        <p>La frase di avviso comparirà all'utente la prima volta che tenta di accedere al sito.</p>
        <p>In alcuni casi, il proprio DES potrebbe imporre un tetto massimo di giorni impostabili.</p>
        </div>

      </div>";

//Gestori Multipli
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Gestori ordine multipli</h2>
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://us.123rf.com/400wm/400/400/darrenwhi/darrenwhi0711/darrenwhi071100001/2166951-folla-su-sfondo-bianco.jpg\" style=\"width:80px;height:80px\">

        <label for=\"referenti_multipli\">Imposta in questo campo il valore (SI/NO) che serve ad abilitare o meno la possibilità di gestione di un ordine da parte di più persone contemporaneamente.</label>
        <input id=\"referenti_multipli\"  name=\"referenti_multipli\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_REFERENTI_MULTIPLI")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_referenti_multipli\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

      </div>";



//Uso cassa
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Gestione cassa</h2>
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://1.bp.blogspot.com/_NEUwANMK3tU/TDxcfgikLNI/AAAAAAAAAAk/tLJRe6Zzt64/s320/diventarericchi.jpg\" style=\"width:80px;height:80px\">
        <label for=\"site_cassa\">Imposta in questo campo il valore (SI/NO) che serve a reteDes per far comparire o nascondere tutti i menu della cassa.</label>
        <input id=\"site_cassa\"  name=\"gas_usa_cassa\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_GAS_USA_CASSA")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

      </div>";

//Uso gas_visione_condivisa
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Visione ordini condivisa</h2>
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <label for=\"site_gas_visione_condivisa\">Se imposti questo campo a SI tutti gli utenti possono vedere cosa comprano gli altri utenti.</label>
        <input id=\"site_gas_visione_condivisa\"  name=\"gas_visione_condivisa\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_GAS_VISIONE_CONDIVISA")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_visione_condivisa\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

      </div>";

//ID utente nella barra info
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Mostra ID utente nella barra info</h2>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.dmv.state.pa.us/graphics/PA_VOTER_ID_LAMINATE.jpg\" style=\"width:80px;height:80px\">

        <label for=\"site_show_userid\">Vuoi vedere l'user_id nella barra info ? (SI/NO)</label>
        <input id=\"site_show_userid\"  name=\"site_show_userid\" value=\"".read_option_gas_text(_USER_ID_GAS,"_SITE_SHOW_USERID")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_show_userid\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

      </div>";

//Hashtag GAS
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Hashtag GAS</h2>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.zeusnews.it/img/4/3/7/7/1/0/017734-twitter.jpg\" style=\"width:80px;height:80px\">
        <label for=\"hashtag_gas\">Hashtag che deve essere incluso nei tweet generati dal tuo gas. MAX 4 CARATTERI</label>
        <input id=\"hashtag_gas\"  name=\"hashtag_gas\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_HASHTAG_GAS")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_hashtag_gas\"></input>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong>
        <p>Immettere l'hashtag SENZA CANCELLETTO</p>
        </div>
      </div>";

//Wordpress ID
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Wordpress ID</h2>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.ognigiorno.com/wp-content/uploads/2011/05/wordpress-logo.png\" style=\"width:80px;height:80px\">
        <label for=\"wpid_gas\">Codice da usare per i WIDGET di WordPress.</label>
        <input id=\"wpid_gas\"  name=\"wpid_gas\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_WPID_GAS")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_wpid_gas\"></input>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";

//Voce pulsante PARTECIPA
$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Pulsante \"PARTECIPA\"</h2>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://retegas.altervista.org/gas2/images/pulsante_zuccone.JPG\" style=\"width:80px;height:80px\">
        <label for=\"pulsante_partecipa\">Un bel pulsantone grosso per ordinare, con una scritta personalizzabile. Se si lascia vuoto non verrà mostrato nulla</label>
        <input id=\"pulsante_partecipa\"  name=\"pulsante_partecipa\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_PULSANTE_PARTECIPA")."\" size=\"50\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_pulsante_partecipa\"></input>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";

//Partecipazione ordini esterni

if(read_option_gas_text_new(_USER_ID_GAS,"PUO_SCEGLIERE_POLITICA_ORDINI")<>"NO"){
     $disabled="";
}else{
     $disabled="DISABLED";
     $warning ="<div class=\"ui-state-error ui-corner-all padding_6px\"><p><strong>Attenzione : </strong> queste impostazioni sono gestite dal tuo DES</p></div>";
}

$h .= "<div class=\"rg_widget rg_widget_helper\">

        <h2>Gestione Ordini</h2>
        $warning
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://3.bp.blogspot.com/-mpssSKD10EY/TxdkXqLkMbI/AAAAAAAAAlM/_5GYwoQ0HDY/s320/receivinggift.jpg\" style=\"width:80px;height:80px\">
        <label for=\"gas_puo_partecipare_ordini_esterni\">Il proprio gas può partecipare agli ordini esterni condivisi ? (SI/NO)</label>
        <input id=\"gas_puo_partecipare_ordini_esterni\" $disabled name=\"gas_puo_partecipare_ordini_esterni\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_puo_partecipare_ordini_esterni\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.beacon.it/wordpress/wp-content/uploads/2012/08/cassetta-150x150.jpg\" style=\"width:80px;height:80px\">
        <label for=\"gas_condivisione_propri_ordini\">Il proprio gas può condividere i propri ordini ai GAS esterni ? (SI/NO)</label>
        <input id=\"gas_condivisione_propri_ordini\" $disabled name=\"gas_condivisione_propri_ordini\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_GAS_PUO_COND_ORD_EST")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_condivisione_propri_ordini\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

      </div>";


//-----------------------------------------------------


//$r->contenuto = rg_toggable("Alcune novit?","poio",$bla,false).$h;
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);