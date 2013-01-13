<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


if(isset($theme_selected)){

switch ((int)$theme_selected){
case 0:
    delete_option_text(_USER_ID,"_USER_OPT_THEME");
    break;
case 1:
    write_option_text(_USER_ID,"_USER_OPT_THEME","WINTER");
    break;
case 2:
    write_option_text(_USER_ID,"_USER_OPT_THEME","SPRING");
    break;
case 3:
    write_option_text(_USER_ID,"_USER_OPT_THEME","NIGHT");
    break;            
case 4:
    write_option_text(_USER_ID,"_USER_OPT_THEME","PAPER_1");
    break;
case 5:
    write_option_text(_USER_ID,"_USER_OPT_THEME","RAIN");
    break;
case 6:
    write_option_text(_USER_ID,"_USER_OPT_THEME","SEPIA");
    break;
case 7:
    write_option_text(_USER_ID,"_USER_OPT_THEME","DRUNKEN");
    break;             
case 8:
    write_option_text(_USER_ID,"_USER_OPT_THEME","GONG");
    break;   
}

go("sommario");
exit();
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Temi";



//Messaggio popup;
$r->messaggio = $msg; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = "";

//Assegno le due tabelle a tablesorter

$r->messaggio = $msg;
//Creo la pagina dei temi

$h = "<div class=\"rg_widget rg_widget_helper\">";
$h.= "<h3>Scelta del tema</h3>";
$h.= "<div class=\"ui-state-error padding_6px ui-corner-all\">
      <h4>ATTENZIONE:</h4>
      <ul>
        <li>Questo è un semplice esperimento di grafica, non ha alcuna attinenza con il gestionale ed i gas.</li>
        <li>Non tutte le pagine del sito hanno la possibilità di mostrare il tema scelto, e non tutte le pagine sono già predisposte a mostrare il tema scelto correttamente.</li>
        <li>Alcune immagini usati sono molto grosse, pertanto potrebbe venire penalizzata la velocità di caricamento delle pagine (almeno la prima volta)</li>
        <li>I temi sono stati testati solo con Chrome e Firefox (e con firefox vanno pianissimo). Con altri browser non so cosa potrebbe accadere.</li>
      </ul>
      </div>
      <br>";
$h.= "<form class=\"retegas_form ui-corner-all\" ACTION=\"\" method=\"POST\">";
$h.= "  <fieldset>
       
        
        <input type=\"radio\" name=\"theme_selected\" value=\"0\" checked><strong> Tema \"Il vero Gasista\"</strong>  lo sfondo è bianco<br>
        <input type=\"radio\" name=\"theme_selected\" value=\"1\"><strong> Tema \"Gasista invernale\"</strong>  lo sfondo è composto da un bosco che sfuma in bianco, vi sono dei fiocchi di neve leggera che cadono, e cambiano la loro traiettoria al passaggio del mouse. Il tema richiede una CPU abbastanza potente, e l'uso di Google Chrome, in quanto le prestazioni con Firefox sono notevolmente ridotte.<br> 
        <input type=\"radio\" name=\"theme_selected\" value=\"2\"><strong> Tema \"Gas di primavera\"</strong>  In un cielo primaverile, volano stormi di rondini che fuggono al passaggio del mouse. Richiede un computer MOLTO potente e l'uso di Google Chrome, in quanto le prestazioni con Firefox sono notevolmente ridotte.<br>
        <input type=\"radio\" name=\"theme_selected\" value=\"3\"><strong> Tema \"The NightGas\"</strong>Colori invertiti...<br>
        <input type=\"radio\" name=\"theme_selected\" value=\"4\"><strong> Tema \"Il Gasista romantico\"</strong>  su carta anticata.<br>
        <input type=\"radio\" name=\"theme_selected\" value=\"5\"><strong> Tema \"Ho messo su un thè\"</strong>.. e intanto che aspetto guardo fuori che piove..<br>
        <input type=\"radio\" name=\"theme_selected\" value=\"6\"><strong> Tema \"Bei tempi andati...\"</strong>..un ritorno al vecchio seppia.<br>
        <input type=\"radio\" name=\"theme_selected\" value=\"7\"><strong> Tema \"'mbriaco\"</strong>...la mia vista... oddio la mia vista...<br>
        <input type=\"radio\" name=\"theme_selected\" value=\"8\"><strong> Tema \"'Gong - Gazeuse\"</strong> psichedelia pura<br>
      </fieldset>";
$h.= "<input type=\"submit\" value=\"Utilizza il tema selezionato\">";       
$h.= "</form>";
$h.= "</div>";

//-----------------------------------------------------


//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)
?> 