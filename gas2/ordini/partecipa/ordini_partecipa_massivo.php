<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Ordine massivo";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.

    $r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Gestione ordini complessi</h3>";
$h_h .= "<div id=\"istr\">
        <p>Questa funzione serve per poter gestire una grossa lista di amici che vogliono partecipare all'ordine.</p>
        <p>Seguire i seguenti punti</p>
            <ul>
                <li>Selezionare gli amici (nella pagina specifica)</li>
                <li>Esportare il file CSV di \"maschera\"</li>
                <li>Il file CSV è configurabile dal menù : \"Opzioni sito\"</li>
                <li>Importarlo nel proprio programma preferito (Excel, google docs, access ecc ecc)</li>
                <li>Salvarlo in formato CSV</li>
                <li>Importarlo sul sito</li>
                <li>Controllare gli importi e Confermare</li>
            </ul>
        </div>";
        

        
$h .= "<div class=\"ui-state-error ui-corner-all padding_6px\">
       <h4>Note</h4>
        <ul>
            <li>QUESTA FUNZIONE NON E' ADOPERABILE CON ARTICOLI UNIVOCI</li>
            <li>Ogni caricamento del file implica <strong>la sostituzione delle quantità preesistenti.</strong></li>
            <li>E' gestita solo la partecipazione, ma NON la modifica delle quantità assegnate ad ordine chiuso (Si può fare manualmente)</li>
        </ul> 
       </div><br>";
       
$h .= rg_toggable("Istruzioni","istr",$h_h);


$destinazione = "ordini_csv_upload.php";
$avvertenze = "";
              

$tag_separatora = 'Separatore di elenco  
                    <input type="text" name="separatore" value=";" size="1" maxlength="1"   title="separatore">
                    <span style="font-size:0.8em; font-weight:normal;">Normalmente è il carattere ";" (Punto e virgola). In alcuni casi può essere la "," (virgola)</span><br>
                    </span>';


$h .= "<div class=\"retegas_form\">
       
       <div>
       <h4>1</h4>
       <label for=\"metti_amici\">Amici attivi</label>
              <a id=\"metti_amici\" class=\"awesome green small\" href=\"".$RG_addr["pag_amici_table"]."?go_back=to_mass&id_ordine=$id_ordine\">Cambia</a>
       <p>
       ".amici_lista_attivi(_USER_ID)."
       </p>
       </div>
       
       
       <div>
       <h4>2</h4>
       <label for=\"esporta_csv\">Esporta il file csv qui</label>
       <a id=\"esporta_csv\" class=\"awesome green small\" href=\"".$RG_addr["ordine_partecipa_out_csv"]."?id_ordine=$id_ordine\">ESPORTA</a>
       </div>
       
       <div>
       <h4>3</h4>
       <a>Modifica il file scaricato con il tuo programma preferito (Excel, google Docs, lotus 123), e salvalo nuovamente in formato CSV</a>
       </div>
       
       <div>
       <h4>4</h4>
               <form action=\"ordini_csv_upload.php\" method=\"post\" enctype=\"multipart/form-data\" >
                Importa il file precedente dopo averlo modificato 
                <input type=\"file\" name=\"upfile\" >
                
                <input type=\"hidden\" name=\"id_ordine\" value=$id_ordine>
                <input type=\"submit\" class=\"awesome green \" value=\"IMPORTA\">
                </form>
       </div>
       
       
         
       </div>";       
       
$h .= "</div>";

//Questo è¨ il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);