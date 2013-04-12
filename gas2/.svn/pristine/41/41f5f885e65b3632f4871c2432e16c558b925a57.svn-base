<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_creare_listini)){
     pussa_via();
}

(int)$id;

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 1;
//Assegno il titolo che compare nella barra delle info
$r->title = "Carica listino";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale[] = listini_menu($user,$id);

//Assegno le due tabelle a tablesorter
//$r->javascripts[]=java_tablesorter("output_1");


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

// --------------START LISTINI
      // UPLOAD
      
     if($tipo_file=="CSV"){$titolo_file="Carica una tabella di testo <b>(.CSV)</b> contenente gli articoli per questo listino";
                           $destinazione = "listini_upload.php";
                           $avvertenze = "<h3>Attenzione : Gli articoli RAGGRUPPATI si possono caricare (per ora) soltanto usando un file EXCEL</h3>
                                          La prima riga è destinata ai nomi delle colonne, pertanto non sarà inclusa dall'importazione.<br>
                                          Alcuni fogli elettronici esportano i file CSV usando dei delimitatori di campo diversi da quelli standard.<br>
                                          Se il file non viene correttamente interpretato provate a sostituirli.<br>
                                          <br>";
                                          
                           $tag_input = "Carica una tabella di testo .csv : <input type=\"file\" name=\"upfile\" class=\"\">";
                           $tag_separatori = 'Separatore di elenco  <input type="text" name="separatore" value=";" size="1" maxlength="1"   title="separatore">
                           <span style="font-size:0.8em; font-weight:normal;">Normalmente è il carattere ";" (Punto e virgola). In alcuni casi può essere la "," (virgola)</span><br>
                           </span><br><br>';};
     if($tipo_file=="XLS"){$titolo_file="Carica un file MS EXCEL <b>(.XLS)</b> contenente gli articoli per questo listino";
                           $destinazione = "listini_upload_xls.php";
                           $avvertenze = "La prima riga è destinata ai nomi delle colonne, pertanto non sarà inclusa dall'importazione.<br>
                                          L'importazione non è stata testata con tutte le versioni esistenti di excel, in caso di insuccesso provare a salvare il listino con il formato di una versione più vecchia di quella in uso.";                             
                           $tag_input = "Carica un file EXCEL : <input type=\"file\" name=\"upfile\">";};
     if($tipo_file=="GOO"){$titolo_file="Incolla qua sotto l'indirizzo di una tabella online creata con <b>GOOGLE DOCS</b> contenente gli articoli per questo listino";
                           $destinazione = "listini_upload_goo.php";
                           $avvertenze = "<h3>Attenzione : Gli articoli RAGGRUPPATI si possono caricare (per ora) soltanto usando un file EXCEL<br>
                                          <div class=\"ui-error\">Si possono importare listini con MAX 100 Articoli.</div></h3>
                                          La prima riga è destinata ai nomi delle colonne, pertanto non sarà inclusa dall'importazione.<br>
                                          <b>Per poter essere importato, il documento GOOGLE deve essere prima \"Pubblicato sul web\".</b><br>
                                          Tra una pubblicazione e la sua effettiva visibilità possono trascorrere alcuni minuti.<br>
                                          Il servizio si appoggia su GoogleDocs. (<a href=\"https://docs.google.com\">docs.google.com</a>)<br>
                                          Qua trovate un link ad un listino pubblico di esempio. <a class=\"silver awesome small\" href=\"https://spreadsheets.google.com/ccc?key=0An0LoUdzBJs0dEhVV3UtTDNkTzVvazd2NlBhQ1JUR1E&hl=it\">Documento_esempio_Google</a><br>";
                                
                           $tag_input = "Link ad un GOOGLE DOC : <input type=\"text\"  size=\"50\" name=\"upfile\">";};                      
     
     $n_articoli_esistenti = articoli_n_in_listino($id); 
     if($n_articoli_esistenti>0){$alert_articoli='<div class="ui-state-error ui-corner-all padding_6px mb6">
                                                    Questo listino contiene già <strong>'.$n_articoli_esistenti.'</strong> articoli. Quelli che tu inserisci ora verranno ACCODATI a quelli esistenti</div><br>';}
     
      
          
      $h_table .= " <div class=\"rg_widget rg_widget_helper\">
                    $alert_articoli
                    $titolo_file
                    <br />
                    $avvertenze
                    <br />
                    <form action=\"$destinazione\" method=\"post\" enctype=\"multipart/form-data\" class=\"retegas_form\">
                                    $tag_separatori 
                                    $tag_input
                                    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000\">
                                    <input type=\"hidden\" name=\"listino\" value=$id>
                                    <input type=\"hidden\" name=\"tipo_file\" value=$tipo_file>
                                    <input type=\"submit\" class=\"awesome green\" value=\"Carica il file\">
                                    </form>
                    </div>
                                    
            ";




//Questo è¨ il contenuto della pagina
$r->contenuto = listini_form($id).$h_table;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>