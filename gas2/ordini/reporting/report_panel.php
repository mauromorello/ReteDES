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
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
//     pussa_via();
}

if(!isset($id_ordine)){
    pussa_via();
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Pannello Report";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "<div class=\"rg_widget rg_widget_helper\">
      <h3>Pannello reports - In fase di allestimento</h3>";
        
//REPORT ARTICOLI 1
$h .="<br>";
$h .="<div>";
$h .="<a class=\"awesome large blue\" href=\"".$RG_addr["report_articoli_per_codice"]."?id_ordine=$id_ordine\">ARTICOLI RAGGRUPPATI TOTALI</a>";
$h .="<p>Report con articoli raggruppati per CODICE; . Sono indicati il CODICE ARTICOLO, la descrizione, il prezzo singolo e la quantità totale richiesta. </p>";
$h .="</div>";
$h .="<hr>";

//REPORT ARTICOLI 1 bis
$h .="<br>";
$h .="<div>";
$h .="<a class=\"awesome large blue\" href=\"".$RG_addr["report_articoli_per_codice_simple"]."?id_ordine=$id_ordine\">ARTICOLI RAGGRUPPATI TOTALI (SEMPLICE)</a>";
$h .="<p>Report con articoli raggruppati per CODICE; Non sono contemplate le scatole. Gli articoli sono quelli <b>ordinati</b> da tutti i GAS. Sono indicati il CODICE ARTICOLO, la descrizione, il prezzo singolo e la quantità totale richiesta. </p>";
$h .="</div>";
$h .="<hr>";

//REPORT ARTICOLI 1 tris
$h .="<br>";
$h .="<div>";
$h .="<a class=\"awesome large blue\" href=\"".$RG_addr["report_articoli_per_codice_2"]."?id_ordine=$id_ordine\">ARTICOLI RAGGRUPPATI PER CODICE e DISTRIBUZIONE PER GAS scatole / avanzo</a>";
$h .="<p>Report con articoli raggruppati per CODICE; Ogni codice poi ha indicata la quantità che va ad ogni gas</p>";
$h .="</div>";
$h .="<hr>";

//REPORT ARTICOLI 1 quater
$h .="<br>";
$h .="<div>";
$h .="<a class=\"awesome large blue\" href=\"".$RG_addr["report_scatole_intere"]."?id_ordine=$id_ordine\">ARTICOLI RAGGRUPPATI PER CODICE <b>SOLO SCATOLE INTERE</b></a>";
$h .="<p>Report con articoli raggruppati per CODICE; Sono considerate SOLO le quantità ORDINATE (e non arrivate) che compongono SCATOLE INTERE; Questo report è utile nel caso si voglia comunicare al fornitore l'ordine già 'tagliato' degli articoli che non chiudono le scatole, anche se non si è ancora rettificato.</p>";
$h .="</div>";
$h .="<hr>";
    
//REPORT ARTICOLI 2
$h .="<br>";
$h .="<div>";
$h .="<a class=\"awesome large blue\" href=\"".$RG_addr["ordini_report_riep_all_gas"]."?id_ordine=$id_ordine\">ARTICOLI RAGGRUPPATI PER GAS</a>";
$h .="<p>Report con articoli raggruppati per ogni singolo GAS partecipante all'ordine; Non sono contemplate le scatole. Gli articoli sono quelli <b>ordinati</b> da tutti i GAS. Sono indicati il CODICE ARTICOLO, la descrizione, il prezzo singolo  e la quantità richiesta da ogni singolo GAS. </p>";
$h .="</div>";
$h .="<hr>";   

//REPORT NOTE PERSONALI
$h .="<br>";
$h .="<div>";
$h .="<a class=\"awesome large blue\" href=\"".$RG_addr["report_riepilogo_note"]."?id_ordine=$id_ordine\">Note personali di questo ordine</a>";
$h .="<p>Report con l'elenco dei partecipante e le loro note relative all'ordine.</p>";
$h .="</div>";
$h .="<hr>";     
    

$h .="</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).
                $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>