<?php



// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

//CONTROLLI
if(ordine_inesistente($id_ordine)){
    pussa_via();
    exit;
}
//CONTROLLO SE L'ORDINE E' DI USER
//Se non sono almeno referente GAS allora non posso vedere nulla.
$mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);

//Se posso vedere tutti gli ordini
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){

    if ($mio_Stato<3){
        go("sommario",_USER_ID,"Questo ordine non mi compete");
    }

}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Rettifica OFFLINE";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
//ISTRUZIONI
$h_h .= "<div id=\"istr\">
        <p>Questa funzione serve per poter gestire la rettifica dei quantitativi di un ordine (articolo/utente) in modalità offline.</p>
        <p>Seguire i seguenti punti</p>
            <ul>
                <li>Esportare il file CSV di \"maschera\"</li>
                <li>Il formato del file CSV è configurabile dal menù : \"Opzioni sito\"</li>
                <li>Importarlo nel proprio programma preferito (Excel, google docs, access ecc ecc)</li>
                <li>Modificare solo i valori della colonna \"NUOVE QUANTITA'\"</li>
                <li>Salvarlo in formato CSV</li>
                <li>Importarlo sul sito</li>
                <li>Controllare gli importi caricati e Confermare</li>
            </ul>
        <p>NOTE : </p>
            <ul>
                <li>L'ordinamento delle righe file csv può essere modificato rispetto all'originale</li>
                <li>Gli articoli che non sono presenti nel file importato non vengono considerati</li>
                <li>TUTTE le colonne devono mantenere la stessa struttura</li>
                <li>Tutti i dati devono essere lasciati come sono (tranne la colonna \"Q_ARR'\")</li>
                <li>Una volta importate le rettifiche funzionano come quelle online, le quantità diverse da quelle precedenti verranno indicate come \"modificate\", quelle a zero come \"annullate\"</li>
                <li>Questa rettifica può essere ripetuta, ogni valore trovato nuovo sostituisce quello vecchio.</li>
                <li>In caso di rettifiche multiple e diverse CONTROLLARE sempre i nuovi importi.</li>
            </ul>
        <p>ATTENZIONE : </p>
            <ul>
                <li>La modifica dei valori viene eseguita immediatamente, all'atto del caricamento.</li>
            </ul>
        </div>";


$h = "<div class=\"rg_widget rg_widget_helper\">";
$h.= "<h3>Rettifica ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).")</h3>";
$h .= rg_toggable("Istruzioni","istr",$h_h);
$h .= "<div class=\"retegas_form\">";
$h .= "<div>
       <h4>1</h4>
       <label for=\"esporta_csv\">Esporta il file csv qui</label>
       <a id=\"esporta_csv\" class=\"awesome green small\" href=\"".$RG_addr["output_csv_rettifica_qta"]."?id_ordine=$id_ordine\">ESPORTA</a>
       </div>";
$h .= "<div>
       <h4>2</h4>
       <p>Modifica SOLO l'ultima colonna con le nuove quantità con il tuo programma preferito.</p>
       </div>
       <div>
       <h4>3</h4>
               <form action=\"rettifica_csv_upload.php\" method=\"post\" enctype=\"multipart/form-data\" >
                Importa il file precedente dopo averlo modificato
                <input type=\"file\" name=\"upfile\" >

                <input type=\"hidden\" name=\"id_ordine\" value=$id_ordine>
                <input type=\"submit\" class=\"awesome green \" value=\"IMPORTA\">
                </form>
       </div>";
$h .= "</div>";
$h.= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);