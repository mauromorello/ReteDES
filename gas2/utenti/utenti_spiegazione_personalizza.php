<?php



   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Spiegazione Personalizzazione";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;
$r->has_bookmark = "SI";

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");

$r->javascripts_header[] =" 
                    <style type=\"text/css\">
                       .big_outline {border: 4px solid red;}
                    </style>";
$r->javascripts[] = '
                    <script type="text/javascript">
                     $(function() {
                        $("#hor").hover(
                      function () {
                        $("#navigation").addClass("big_outline",1000);
                      }, 
                      function () {
                        $("#navigation").removeClass("big_outline",1000);
                      }
                    );
                    });
                    </script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "  <div class=\"rg_widget rg_widget_helper\">
        <h3>Personalizzazione della Home Page</h3>
        <h4 id=\"hor\">Barra orizzontale</h4>
        <p>Alcune pagine del sito presentano in alto a destra un pulsante con il simbolo di un piccolo cuore: se si clicca sopra, il collegamento a questa
        pagina viene trasformato in un collegamento rapido nella pagina principale.<br>
        Per eliminare un collegamento esistente è sufficiente cliccare nuovamente sull'icona (rossa) del cuoricino.<br>
        </p>
        <h4>Barra Verticale</h4>
        <p>
        La posizione delle voci della barra verticale può essere modificata a piacere; per farlo tenere cliccato a lungo su di un elemento e poi trascinarlo nella nuova posizione; Le impostazioni saranno salvate e varranno per tutte le pagine
        del sito;
        </p>
        <h4>Contenuto</h4>
        <p>
        La posizione, la quantità e gli oggetti all'interno della homepage possono essere variati apiacere. I singoli elementi, che si chiamano \"widget\" possono essere
        selezionati tra quelli disponibili dalla pagina apposita. Una volta presenti e visibili, possono essere spostati tenendo cliccato a lungo sulla loro testata e trascinandoli nella posizione voluta. 
        Ogni widget può avere questi pulsanti:
        <ul>
            <li>Impostazioni : valgono per il singolo widget</li>
            <li>Ricerca : trova velocemente valori al suo interno</li>
            <li>Apri e chiudi: per compattare o espandere il widget</li>
        </ul>
        </p>
        </div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>