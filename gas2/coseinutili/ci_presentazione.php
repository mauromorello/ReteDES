<?php


   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     //pussa_via(); 
}else{
    $tw =  "#coseinutili da #retedes interessa a qualcuno del ".gas_nome(_USER_ID_GAS)." ("._USER_ID * 12 .")";
    $res = tweet($tw);
    log_me(0,_USER_ID,"CSI","VIS",_USER_FULLNAME." ha visitato questa pagina",$res,$tw);
     
 
}    




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::coseinutili;
//Assegno il titolo che compare nella barra delle info
$r->title = "Presentazione Cose(in)utili";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;
$r->javascripts[] ="<script type=\"text/javascript\">
                  $(\"#menu_hor\").hide();
                  $(\"#linea_sotto_menu\").hide();
                  $(\"#content\").css(\"margin-top\", 0);
                  $(\"#content\").css(\"padding-top\", 0);
                  </script>";

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "<div class=\"rg_widget rg_widget_helper\" style=\"font-size:1.2em;\">";
$h .="<h1><img src=\"http://www.coseinutili.it/images/common/logo2.png\"><h1>";
$h .="<h3>Che cos'è ?</h3>";
$h .="<p>È un sito che ti permette di pubblicare annunci per lo scambio di oggetti o di prestazioni di tempo.
La filosofia che lo ha fatto nascere è illustrata <strong><a href=\"http://www.coseinutili.it/coseinutili/\" target=\"blank\">qui.</a></strong>
È una forma “evoluta” di baratto: non è necessario che lo scambio sia diretto (tu dai una cosa a me e io ne do una a te), ma può essere effettuato con qualunque membro della comunità, perché è regolato da un sistema di crediti. <strong>(*)</strong></p>";
$h .="<h3>Come funziona ?</h3>";
$h .="<p>Hai un oggetto che vorresti barattare, oppure vuoi mettere a disposizione il tuo tempo.
Pubblichi un annuncio assegnando a quell’oggetto un certo numero di crediti. Le prestazioni di tempo invece valgono tutte 12 crediti all’ora. Chi è interessato al tuo annuncio ti contatta e avviene lo scambio.
In quel momento, i crediti che lui “ti deve” passano a te, e puoi usarli per ottenere altri oggetti o altre prestazioni di tempo. <strong>(*)</strong></p>";
$h .="<h3>Cosa c'entra con ReteDes.it ?</h3>";
$h .="<p>Mi è stato proposto da più persone di gestire anche la banca del tempo ed una specie di mercatino all'interno di ReteDES.it, ma fortunatamente ho
         trovato chi aveva già svolto tutto questo lavoro egregiamente. 
         Cose(in)utili è un sito indipendente a livello nazionale, e così è nata una collaborazione concreta che permette di completare la parte di scambistica e di banca del tempo. Un altro vantaggio della
         collaborazione con Cose(in)utili è che gestisce e raggruppa gli utenti appartenenti allo stesso gas. E' così comodo trovare e barattare oggetti all'interno della propria cerchia di amici.
</p>";
$h .="<h3>Come faccio per partecipare ?</h3>";
$h .="<p>Dopo aver cliccato, completa la pagina con i dati richiesti, specificando di appartenere al <strong>".gas_nome(_USER_ID_GAS)."</strong></p>";
$h .="
<p>
<strong>(*)</strong>
<cite>NB : Il sistema dei crediti di Cose(in)utili NON fa parte e non è gestito dalla cassa di ReteDes; Sono due sistemi completamente separati.</cite>
</p>

<center><a class=\"awesome large green\" href=\"http://www.coseinutili.it/iscrizione/\" TARGET=_BLANK >MI ISCRIVO A COSE(IN)UTILI</a></center>";
$h .="</div>";

   
//Questo  il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);