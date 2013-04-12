<?php


   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::coseinutili;
//Assegno il titolo che compare nella barra delle info
$r->title = "Oggetti Cose(in)utili";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto

$llz = lat_lon_from_id(_USER_ID);
$llz .= ",".lon_lat_from_id(_USER_ID);
$llz .= ",10";

$i = "<h4>Come funziona la mappa?</h4>
      <p>La mappa è composta da una mappa (...) una lista di categorie (in basso) e una lista di oggetti (a destra). Le categorie raggruppano le
      varie tipologie di oggetti, si possono includere o escludere dalla vista.<br>
      L'elenco degli oggetti a destra include TUTTI quelli visibili all'interno della mappa (se si zoomma logicamente diminuiscono).</p>
      <h4>Raggruppamento</h4>
      <p>Quando la mappa è vista 'da lontano' (ad esempio mostra l'italia intera) gli oggetti si raggruppano, 
      e vengono rappresentati da un logo di cose(in)utili con indicata la loro quantità in quella zona.</p>
      <h4>Tanti oggetti nello stesso luogo</h4>
      <p>Se un utente ha molti oggetti da barattare, essi compariranno impilati nello stesso luogo. Se vi si clicca sopra si potranno 'esplodere' in modo da poterli vedere tutti.</p>
      <h4>Il baratto</h4>
      <p>Tutte le operazioni di Baratto o scambio avvengono all'interno del sito Cose(in)utili, e occorre esservi registrati per poter usufruire del servizio.<br>
      Per poter vedere la scheda completa dell'oggetto occorre cliccare su 'dettaglio' in basso a destra della finestra con la descrizione dell'oggetto.</p>
      <h4>Disclaimer</h4>
      <p>Tutte le operazioni riguardanti Cose(in)utili sono regolamentate dal disclaimer raggiungibile dalla homepage.</p>";

$i = rg_toggable("Istruzioni","istr",$i,false);      

$h = "<div class=\"rg_widget rg_widget_helper\">$i<iframe frameborder=0 src=\"http://www.coseinutili.it/geo/maps/index.php?llz=$llz\" style=\"width:800px;height:680px;\"></iframe></div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>