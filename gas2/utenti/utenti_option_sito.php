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
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opzioni Sito";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.

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
        La stampa proveniente dal referente ordine è quella a cui fare riferimento.</div>
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
        <div class=\"ui-state-error ui-corner-all padding_6px\">Questo carattere può essere cambiato per gestire le esportazioni delle tabelle a seconda del sistema operativo usato.</div>
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
        <label for=\"nositeheader\">Nascondi l'intestazione del sito, in modo da avere più spazio per i dati. (SI/NO)</label>
        <input id=\"nositeheader\" name=\"nositeheader\" value=\"".read_option_text(_USER_ID,"_USER_OPT_NO_SITE_HEADER")."\" size=\"4\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_nositeheader\"></inupt>
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
            <p>La cassa ReteDes.it è un sistema di Crediti/Debiti nei confronti del proprio gas, gestito in parte attraverso questo sito. Il cassiere designato, usando
            questo strumento, è facilitato nella gestione del PROPRIO REGISTRO CONTABILE, il quale è l'unica copia
            ufficiale che testimonia gli avvenuti pagamenti o accrediti. Quindi, come descritto nel <a href=\"http://disclaimer.retedes.it\">DISCLAIMER</a> Il sito ReteDes.it <br><h4><cite>declina ogni responsabilità per le conseguenze che possano essere arrecate 
            ad Utenti o terzi da possibili malfunzionamenti del Portale e per gli eventuali danni di qualsiasi natura in cui dovessero incorrere gli Utenti, compresi eventuali guasti, inesattezze, interruzioni 
            della disponibilità o funzionalità del database sul quale il sito si appoggia;</cite></h4>
            <h4><cite>
            Sono da considerarsi malfunzionamenti anche le possibili errate contabilizzazioni degli importi calcolati, le assegnazioni di merce, le rettifiche degli articoli e il calcolo delle percentuali sui costi riferite ad ogni utente, ed ogni altra operazione che dia dei risultati valori sia calcolati che semplicemente letti dal database, sia con output a video piuttosto che cartaceo.
            </cite></h4></p>
            <p>La accettazione o meno di voler usufruire della cassa, è libera, ma vi potranno essere degli ordini di materiale
            ai quali si può partecipare solo usando Crediti ReteDes.it</p>
            <p>I metodi e i termini di pagamento e di ricarica crediti sono gestiti autonomamente dal proprio GAS, e non sono in nessun modo vincolati nè veicolati dal sito.</p>
                    
            </div>

            <form method=\"post\" action=\"\" class=\"retegas_form\">
            
            <label for=\"usa_cassa\">Usando la cassa vengono gestite in automatico le operazioni sui crediti</label>
            <input id=\"usa_cassa\" name=\"usa_cassa\" value=\"".read_option_text(_USER_ID,"_USER_USA_CASSA")."\" size=\"4\"></input>
            <input type=\"hidden\" name=\"do\" value=\"do_usa_cassa\"></inupt>
            <input type=\"submit\" value=\"salva\"></input>
            </form>
            <div class=\"ui-state-error ui-corner-all padding_6px\">
            <strong>Attenzione :</strong> 
            <p>Una volta che si è scelto di usare la cassa, per poter disattivare questa opzione è necessario contattare il proprio cassiere.</p> 
            </div>
          
          </div>";      
}      
//-----------------------------------------------------


//Questo è¨ il contenuto della pagina
$bla = '<p>Sono state apportate alcune modifiche che riguardano la gestione delle opzioni personali del sito, una sezione che avevo a lungo trascurato. Le cose più evidenti riguardano:</p>
                 <ul>
                    <li>Le opzioni delle mail, erano troppe contorte da gestire. Ora vi è da decidere se si vogliono ricevere mail dal sito oppure no.</li>
                    <li>Non si può più decidere se comparire online oppure no. La nuova linea è quella di essere visibile all\'interno del proprio GAS, ma di essere invisibile per tutti gli altri. (se non sotto forma di numero anonimo).</li>
                    <li>Vi sono altre opzioni che riguardano la formattazione delle pagine stampate e quelle visualizzate</li>
                    <li>...</li>
                 </ul>
                 <p>Il nuovo "motore" delle opzioni che ho usato è molto più flessibile di quello vecchio, pertanto volendo si potrebbe portare una personalizzazione molto elevata del sito.</p>
                 <p>Ogni suggerimento in tal senso è molto gradito.</p>
                 ';

$r->contenuto = rg_toggable("Alcune novità","poio",$bla,false).$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)    
    
?>