<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

if($do=="save_decimals"){
    write_option_integer(_USER_ID,"_USER_OPT_DECIMALS",CAST_TO_INT($decimals,1,4));
    sleep(1);
    $msg="Numero di decimali modificato";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_DECIMALS : $decimals",0,null);
}
if($do=="save_zoom_fonts"){
    write_option_integer(_USER_ID,"_USER_OPT_ZOOM_FONTS",CAST_TO_INT($zoom_fonts,80,120));
    sleep(1);
    $msg="Dimensione caratteri modificata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_ZOOM_FONTS : $zoom_fonts",0,null);
}

if($do=="save_alert_days"){
    write_option_integer(_USER_ID,"_USER_ALERT_DAYS",CAST_TO_INT($alert_days,0,3));
    sleep(1);
    $msg="Giorni modificati";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_ALERT_DAYS : $alert_days",0,null);
}
if($do=="save_permetti_modifica"){
    $permetti_modifica=strtoupper($permetti_modifica);
    if($permetti_modifica=="SI"){
        write_option_text(_USER_ID,"_USER_PERMETTI_MODIFICA","SI");
    }else{
        write_option_text(_USER_ID,"_USER_PERMETTI_MODIFICA","NO");
    }
    sleep(1);
    $msg="Intestazione  modificata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_PERMETTI_MODIFICA : $permetti_modifica",0,null);
}
if($do=="save_noheader"){
    $noheader=strtoupper($noheader);
    if($noheader=="SI"){
        write_option_text(_USER_ID,"_USER_OPT_NO_HEADER","SI");
    }else{
        write_option_text(_USER_ID,"_USER_OPT_NO_HEADER","NO");
    }
    sleep(1);
    $msg="Intestazione stampe modificata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_NO_HEADER : $noheader",0,null);
}
if($do=="save_nositeheader"){
    $nositeheader = strtoupper($nositeheader);
    if($nositeheader=="SI"){
        write_option_text(_USER_ID,"_USER_OPT_NO_SITE_HEADER","SI");
    }else{
        write_option_text(_USER_ID,"_USER_OPT_NO_SITE_HEADER","NO");
    }
    sleep(1);
    $msg="Intestazione sito modificata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_NO_SITE_HEADER : $nositeheader",0,null);
}
if($do=="save_forza_desktop"){
    $forza_desktop = strtoupper($forza_desktop);
    if($forza_desktop=="SI"){
        write_option_text(_USER_ID,"_USER_OPT_FORZA_DESKTOP","SI");
    }else{
        write_option_text(_USER_ID,"_USER_OPT_FORZA_DESKTOP","NO");
    }
    sleep(1);
    $msg="Forzatura versione effettuata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_FORZA_DESKTOP : $forza_desktop",0,null);
}

if($do=="save_forza_v2"){
    $forza_v2 = strtoupper($forza_v2);
    if($forza_v2=="SI"){
        write_option_text(_USER_ID,"_USER_OPT_FORZA_V2","SI");
    }else{
        write_option_text(_USER_ID,"_USER_OPT_FORZA_V2","NO");
    }
    sleep(1);
    $msg="Forzatura versione effettuata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_FORZA_V2 : $forza_v2",0,null);
}

if($do=="save_sitemail"){
    $sitemail = strtoupper($sitemail);
    if($sitemail=="SI"){
        write_option_text(_USER_ID,"_USER_OPT_SEND_MAIL","SI");
    }else{
        write_option_text(_USER_ID,"_USER_OPT_SEND_MAIL","NO");
    }
    sleep(1);
    $msg="Impostazione Mail modificata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_SEND_MAIL : $sitemail",0,null);

}

if($do=="do_usa_cassa"){
    $usa_cassa = strtoupper($usa_cassa);
    if($usa_cassa=="SI"){
        write_option_text(_USER_ID,"_USER_USA_CASSA","SI");
        $msg ="Impostazione Cassa modificata";
    }else{
        if(_USER_USA_CASSA){
            $msg = "Per toglierti dalla cassa devi contattare il tuo cassiere";
        }else{
            write_option_text(_USER_ID,"_USER_USA_CASSA","NO");
        }
    }
    sleep(1);

    log_me(0,_USER_ID,"OPZ","MOD","_USER_USA_CASSA : $usa_cassa",0,null);
}

if($do=="save_usa_tooltips"){
    $usa_tooltips = strtoupper($usa_tooltips);
    if($usa_tooltips=="SI"){
        write_option_text(_USER_ID,"_USER_USA_TOOLTIPS","SI");
    }else{
        write_option_text(_USER_ID,"_USER_USA_TOOLTIPS","NO");
    }
    sleep(1);
    $msg="Impostazione Messaggini modificata";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_USA_TOOLTIPS : $usa_tooltips",0,null);
}

if($do=="csv_options"){

        switch ($csv_delimiter) {
            case '1':
                $csv_d = '"';
                break;
            case "2":
                $csv_d = "\'";
                break;
            default:
                $csv_d = "";
                break;
        }
        write_option_text(_USER_ID,"_USER_CSV_DELIMITER",$csv_d);
        usleep(500);
        switch ($csv_separator) {
            case '1':
                $csv_s = ',';
                break;
            case "2":
                $csv_s = ";";
                break;
        }
        write_option_text(_USER_ID,"_USER_CSV_SEPARATOR",$csv_s);
        usleep(500);

        switch ($csv_eol) {
            case '2':
                $csv_e = 'n';
                break;
            default:
                $csv_e = 'rn';
                break;
        }

        write_option_text(_USER_ID,"_USER_CSV_EOL",$csv_e);
        usleep(500);

        switch ($csv_zero) {
            case '1':
                $csv_z = '0';
                break;
            default:
                $csv_z = '';
                break;
        }

        write_option_text(_USER_ID,"_USER_CSV_ZERO",$csv_z);
        usleep(500);
    $msg="Impostazione CSV modificata";
    log_me(0,_USER_ID,"OPZ","MOD","CSV: $csv_s $csv_e $csv_z",0,null);
}


if($do=="save_show_debug"){

    write_option_text(_USER_ID,"_USER_OPT_SHOW_DEBUG",CAST_TO_INT($show_debug,0,100));

    sleep(1);
    $msg="Impostazione Debug";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_OPT_SHOW_DEBUG: $show_debug",0,null);
}

if($do=="do_carattere_decimale"){

    if($carattere_decimale<>"."){
       $carattere_decimale = ",";
    }
    write_option_text(_USER_ID,"_USER_CARATTERE_DECIMALE",$carattere_decimale);

    sleep(1);
    $msg="Carattere decimale modificato";
    log_me(0,_USER_ID,"OPZ","MOD","_USER_CARATTERE_DECIMALE: $carattere_decimale",0,null);
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opzioni Sito";


//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale men? orizzontale dovr?? essere associato alla pagina.

$r->menu_orizzontale[] = menu_visualizza_user(_USER_ID);
$r->menu_orizzontale[] = menu_gestisci_user(_USER_ID,$id_utente);
$r->menu_orizzontale[] = menu_gestisci_user_cassa(_USER_ID,$id_utente);


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//--------------------------------------------CONTENUTO
//SITE MAIL
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Ricevi mail personali dal sito (SI/NO)</h3>
        <label for=\"sitemail\">Se imposti 'no' non riceverai mail personali attraverso il sito.</label>
        <input id=\"sitemail\" name=\"sitemail\" value=\"".read_option_text(_USER_ID,"_USER_OPT_SEND_MAIL")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_sitemail\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        <br>
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <b>Attenzione :</b> NON tutte le comunicazioni possono essere interrotte;<br>
        alcune di esse, in particolar modo quelle generate in automatico, potrebbero non essere filtrate da questa impostazione.<br>
        </div>
        </form>
      </div>";

//DECIMALI
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Cifre Decimali (1..4)</h3>
        <label for=\"decimals\">Cifre decimali dopo la virgola nelle pagine di riassunto ordini</label>
        <input id=\"decimals\" name=\"decimals\" value=\"".read_option_integer(_USER_ID,"_USER_OPT_DECIMALS")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_decimals\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        <br>
        <div class=\"ui-state-error ui-corner-all padding_6px\">Gli importi da corrispondere ai referenti ordine non sono influenzati dall'eventuale arrotondamento derivante dall'uso di poche cifre decimali;<br>
        La stampa proveniente dal referente ordine ? quella a cui fare riferimento.</div>
        </form>
      </div>";

//DIMENSIONI FONT
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Dimensioni font (80 -- 120 %)</h3>
        <label for=\"zoom_fonts\">Quanto verranno rimpicciolite o ingrandite le scritte nelle pagine, rispetto ai menu, da 80 a 120 (100 = Dimensione normale)</label>
        <input id=\"zoom_fonts\" name=\"zoom_fonts\" value=\"".read_option_integer(_USER_ID,"_USER_OPT_ZOOM_FONTS")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_zoom_fonts\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        <br>
        </form>
      </div>";

//modifica ordini
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Permetti modifica ordini (SI/NO)</h3>
        <label for=\"permetti_modifica\">Se imposti questo valore su SI permetti ai gestori degli ordini di poter modificare gli ordini fatti da te, aggiungendo o togliendo della merce, oltre che rettificando le quantità di merce acquistata</label>
        <input id=\"permetti_modifica\" name=\"permetti_modifica\" value=\"".read_option_text(_USER_ID,"_USER_PERMETTI_MODIFICA")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_permetti_modifica\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        <br>

        </form>
      </div>";



//ALERT DAYS
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Avviso pre-chiusura (0=NO .. 3)</h3>
        <label for=\"alert_days\">Quanti giorni prima della chiusura di ogni ordine deve partire una mail per avvisare l'utente. (0 = disattivato)</label>
        <input id=\"alert_days\" name=\"alert_days\" value=\"".read_option_integer(_USER_ID,"_USER_ALERT_DAYS")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_alert_days\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        <br>

        </form>
      </div>";

//CARATTERE DECIMALE
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Carattere dei decimali</h3>
        <label for=\"carattere_decimale\">Carattere usato per gestire i decimali</label>
        <input id=\"carattere_decimale\" name=\"carattere_decimale\" value=\"".read_option_text(_USER_ID,"_USER_CARATTERE_DECIMALE")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"do_carattere_decimale\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        <br>
        <div class=\"ui-state-error ui-corner-all padding_6px\">Questo carattere pu? essere cambiato per gestire le esportazioni delle tabelle a seconda del sistema operativo usato.</div>
        </form>
      </div>";


//CSV

switch (read_option_text(_USER_ID,"_USER_CSV_DELIMITER")) {
    case '"':
        $csv_d = '&quot;';
        $virgolette = " SELECTED ";
        break;
    case "'":
        $csv_d = "&apos;";
        $apostrofo = " SELECTED ";
        break;
    default:
        $nullo = " SELECTED ";
        $csv_d = "Nessuno";
        break;
}

switch (read_option_text(_USER_ID,"_USER_CSV_SEPARATOR")) {
    case ',':
        $csv_s = ',';
        $virgola = " SELECTED ";
        break;
    case ";":
        $csv_s = ";";
        $puntoevirgola = " SELECTED ";
        break;
}

switch (read_option_text(_USER_ID,"_USER_CSV_EOL")) {

    case 'n':
        $eol_r = ' SELECTED ';
        $csv_e = '\n';
        break;
    case 'rn':
        $eol_rn = ' SELECTED ';
        $csv_e = '\r\n';
        break;
}
switch (read_option_text(_USER_ID,"_USER_CSV_ZERO")) {

    case '0':
        $zero_zero = ' SELECTED ';
        $csv_zero = '0';
        break;
    default:
        $zero_nullo = ' SELECTED ';
        $csv_zero = 'Nullo';
        break;
}

$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Impostazioni formato CSV</h3>
        <div>
        <label for=\"csv_delimiter\">Delimitatore di campo, <b>attualmente: $csv_d</b></label>
              <select id=\"csv_delimiter\" name=\"csv_delimiter\">
                <option value=\"1\" $virgolette>&quot;</option>
                <option value=\"2\" $apostrofo>&apos;</option>
                <option value=\"3\" $nullo>Nessuno</option>
              </select>
        </div>
        <div>
        <label for=\"csv_separator\">Separatore di campo (\",\" ,\";\") <b>attualmente: $csv_s</b></label>
        <select id=\"csv_separator\" name=\"csv_separator\">
                <option value=\"1\" $virgola>,</option>
                <option value=\"2\" $puntoevirgola>;</option>
              </select>
        </div>
        <div>
        <label for=\"csv_zero\">Rappresentazione dello 0 (0 o nullo) <b>attualmente: $csv_zero</b></label>
        <select id=\"csv_zero\" name=\"csv_zero\">
                <option value=\"1\" $zero_zero>0</option>
                <option value=\"2\" $zero_nullo>nullo</option>
              </select>
        </div>
        <div>
        <label for=\"csv_eol\">Fine riga (\\r\\n o \\n),<br><b>attualmente: $csv_e</b> cambiare solo se si sa cosa si sta facendo.</label>
        <select id=\"csv_eol\" name=\"csv_eol\">
                <option value=\"1\" $eol_rn>\\r\\n</option>
                <option value=\"2\" $eol_r>\\n</option>
        </select>

        <input type=\"hidden\" name=\"do\" value=\"csv_options\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </div>
        </form>
      </div>";



//HEADER
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Nascondi Intestazione stampe</h3>
        <label for=\"noheader\">Nascondi l'intestazione ordine nelle stampe (SI/NO)</label>
        <input id=\"noheader\" name=\"noheader\" value=\"".read_option_text(_USER_ID,"_USER_OPT_NO_HEADER")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_noheader\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";

//TOOLTIPS
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Usa aiutini</h3>
        <label for=\"usa_tooltips\">Mostra o Nascondi I messaggini di aiuto che compaiono quando passi il mouse sopra certi elementi<br>
        Alcuni messaggini sono fissi e non possono essere esclusi. (SI/NO)</label>
        <input id=\"usa_tooltips\" name=\"usa_tooltips\" value=\"".read_option_text(_USER_ID,"_USER_USA_TOOLTIPS")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_usa_tooltips\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";

//SITE HEADER
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Nascondi Intestazione sito</h3>
        <label for=\"nositeheader\">Nascondi l'intestazione del sito, in modo da avere pi? spazio per i dati. (SI/NO)</label>
        <input id=\"nositeheader\" name=\"nositeheader\" value=\"".read_option_text(_USER_ID,"_USER_OPT_NO_SITE_HEADER")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_nositeheader\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";

//FORZA DESKTOP
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Forzatura DESKTOP</h3>
        <label for=\"forza_desktop\">Forza la versione desktop anche sui dispositivi mobili (SI/NO)</label>
        <input id=\"forza_desktop\" name=\"forza_desktop\" value=\"".read_option_text(_USER_ID,"_USER_OPT_FORZA_DESKTOP")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_forza_desktop\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";
//FORZA V2
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Forzatura Versione 2</h3>
        <label for=\"forza_v2\">Forza la versione vecchia di retedes dopo il login (SI/NO)</label>
        <input id=\"forza_v2\" name=\"forza_v2\" value=\"".read_option_text(_USER_ID,"_USER_OPT_FORZA_V2")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_forza_v2\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";

//SHOW DEBUG
if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Mostra informazioni di debug (SI/NO)</h3>
        <label for=\"show_debug\">Per capire cosa accade.</label>
        <input type=\"numeric\" id=\"show_debug\" name=\"show_debug\" value=\"".read_option_text(_USER_ID,"_USER_OPT_SHOW_DEBUG")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_show_debug\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";
}

//CASSA  (se il gas non ha l'opzione attivata allora l'utente non vede questa opzione)
if(_GAS_USA_CASSA){
    $h .= "<div class=\"rg_widget rg_widget_helper\" style=\"font-size:1.2em\">
            <h3>Usa Cassa (SI/NO)</h3>
            <div class=\"ui-state-highlight ui-corner-all padding_6px\">
            <h3>Attenzione : LEGGERE ATTENTAMENTE PRIMA DI PROCEDERE</h3>
            <p>La cassa ReteDes.it ? un sistema di Crediti/Debiti nei confronti del proprio gas, gestito in parte attraverso questo sito. Il cassiere designato, usando
            questo strumento, ? facilitato nella gestione del PROPRIO REGISTRO CONTABILE, il quale ? l'unica copia
            ufficiale che testimonia gli avvenuti pagamenti o accrediti. Quindi, come descritto nel <a href=\"http://disclaimer.retedes.it\">DISCLAIMER</a> Il sito ReteDes.it <br><h4><cite>declina ogni responsabilit? per le conseguenze che possano essere arrecate
            ad Utenti o terzi da possibili malfunzionamenti del Portale e per gli eventuali danni di qualsiasi natura in cui dovessero incorrere gli Utenti, compresi eventuali guasti, inesattezze, interruzioni
            della disponibilit? o funzionalit? del database sul quale il sito si appoggia;</cite></h4>
            <h4><cite>
            Sono da considerarsi malfunzionamenti anche le possibili errate contabilizzazioni degli importi calcolati, le assegnazioni di merce, le rettifiche degli articoli e il calcolo delle percentuali sui costi riferite ad ogni utente, ed ogni altra operazione che dia dei risultati valori sia calcolati che semplicemente letti dal database, sia con output a video piuttosto che cartaceo.
            </cite></h4></p>
            <p>La accettazione o meno di voler usufruire della cassa, ? libera, ma vi potranno essere degli ordini di materiale
            ai quali si pu? partecipare solo usando Crediti ReteDes.it</p>
            <p>I metodi e i termini di pagamento e di ricarica crediti sono gestiti autonomamente dal proprio GAS, e non sono in nessun modo vincolati n? veicolati dal sito.</p>

            </div>

            <form method=\"post\" action=\"\" class=\"retegas_form\">

            <label for=\"usa_cassa\">Usando la cassa vengono gestite in automatico le operazioni sui crediti</label>
            <input id=\"usa_cassa\" name=\"usa_cassa\" value=\"".read_option_text(_USER_ID,"_USER_USA_CASSA")."\" size=\"4\"></input>
            <input type=\"hidden\" name=\"do\" value=\"do_usa_cassa\"></inupt>
            <input type=\"submit\" value=\"salva\"></input>
            </form>
            <div class=\"ui-state-error ui-corner-all padding_6px\">
            <strong>Attenzione :</strong>
            <p>Una volta che si ? scelto di usare la cassa, per poter disattivare questa opzione ? necessario contattare il proprio cassiere.</p>
            </div>

          </div>";
}
//-----------------------------------------------------




$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r)

?>