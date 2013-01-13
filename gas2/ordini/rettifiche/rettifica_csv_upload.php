<?php
//Togliere quelli che non interessano
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

if(!_USER_LOGGED_IN){
    pussa_via();
    exit;
}
 

 
// QUESTE RIGHE RENDONO LO SCRIPT COMPATIBILE CON LE VERSIONI
// DI PHP PRECEDENTI ALLA 4.1.0
if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
if(!isset($_SERVER)) $_SERVER = $HTTP_SERVER_VARS;


$upload_dir = "./uploads";

$new_name = time()+rand(0,100000).".csv";
$old_name_csv = $_FILES["upfile"]["name"];

$rep .= "<h4>START LOG</h4>";


// Se $new_name ? vuota, il nome sar? lo stesso del file uploadato
$file_name = ($new_name) ? $new_name : $_FILES["upfile"]["name"];
$filone = $upload_dir."/".$file_name; 


if(!isset($file_to_load)){

    if(trim($_FILES["upfile"]["name"]) == "") {

        $rep .="<b class=\"ui-state-error\">Non hai indicato il file da uploadare !</b><br>";
        $warning++;
    }

    if(@is_uploaded_file($_FILES["upfile"]["tmp_name"])) {

    @move_uploaded_file($_FILES["upfile"]["tmp_name"], "$upload_dir/$file_name") 
    or die("Impossibile spostare il file, controlla l'esistenza o i permessi della directory dove fare l'upload.");

    } else {
        $rep .= "<b class=\"ui-state-error\">Problemi nell'upload del file " . $_FILES["upfile"]["name"]."</b><br>";
        $warning++;
    }
    $rep .= "CSV $old_name_csv caricato, renamed $new_name<br>";
}else{
   $file_name = sanitize($file_to_load);
   $rep .= "CSV $file_to_load caricato.<br>";    
}

$row = 0;
$fd = fopen ($upload_dir."/".$file_name, "r");
$separatore = _USER_CSV_SEPARATOR;
if(!isset($separatore)){$separatore=",";}

$rep .= "Separatore : $separatore<br><hr>";


//PRIMA RIGA
// Id ordine,  Id user,  Totale amici, 0, amico_1, amico_n ,0
$data = fgetcsv($fd, 1000, "$separatore");
$rep .= "Carico riga intestazione<br>";

//ORDINE
if($data[0]<>$id_ordine){
    $rep .= "<b class=\"ui-state-error\">CSV CON ORDINE NON COMPATIBILE</b> - Controllare impostazioni CSV<br>";
    $warning++;
}

if($data[1]<>_USER_ID){
    $rep .= "<b class=\"ui-state-error\">CSV CREATO DA UN ALTRO UTENTE</b> - Controllare impostazioni CSV<br>";
    $warning++;
}

  
 //SECONDA RIGA (intestazioni)  
 $data = fgetcsv($fd, 1000, "$separatore");
 $rep .= "Caricata riga Titoli<br>";
 // 0 = Cod art GAs, 1 = Articolo, 2 = Descrizione, 3 = mestesso, 4... n amici, n+1 = totale riga
 


 if($warning==0){
     
     //SI APRONO LE DANZE
        $t .="<h3>Importazione file di rettifiche.</h3>";
        $t .="<table id=\"output_1\">";
        $t .="<thead>";
        $t .="<tr>";
        $t .="<th>Nome utente</th>";
        $t .="<th>Gas appartenenza</th>";
        $t .="<th>Codice articolo</th>";
        $t .="<th>Desc. Articolo</th>";
        $t .="<th>Qta ORDINATA</th>";
        $t .="<th>Qta ARRIVATA</th>";
        $t .="</tr>";
        $t .="</thead>";
        $t .="<tbody>";
     
     $riga=2;
     while (($data = fgetcsv($fd, 1000, "$separatore")) !== FALSE) {
         $riga++;
         
         $id_riga_dettaglio = CAST_TO_INT($data[4]);
         if(db_val_q("id_dettaglio_ordini",$id_riga_dettaglio,"id_ordine","retegas_dettaglio_ordini")==$id_ordine){
                $checksum_csv = $data[6];
              
                $id_utente = db_val_q("id_dettaglio_ordini",$id_riga_dettaglio,"id_utenti","retegas_dettaglio_ordini");
                $fullname = fullname_from_id($id_utente);
                $nomegas = gas_nome(id_gas_user($id_utente));
                $codice_articolo = articolo_suo_codice(db_val_q("id_dettaglio_ordini",$id_riga_dettaglio,"id_articoli","retegas_dettaglio_ordini"));
                $riga = $id_riga_dettaglio;
                $desc_art = articolo_sua_descrizione(db_val_q("id_dettaglio_ordini",$id_riga_dettaglio,"id_articoli","retegas_dettaglio_ordini"));
                $qta_ord = db_val_q("id_dettaglio_ordini",$id_riga_dettaglio,"qta_ord","retegas_dettaglio_ordini");
              
                $checksum_ord = crc32(  $id_utente.
                                        $fullname.
                                        $nomegas.
                                        $codice_articolo.
                                        $riga.
                                        $desc_art.
                                        $qta_ord);
                $rep .="Riga $id_riga_dettaglio corrispondente all'ordine $id_ordine, CRC csv = $checksum_csv CRC ord = $checksum_ord<br>";
                
                
                //SE CORRISPONDE IL CRC tra il CSV e i dati estratti dal db
                if($checksum_ord==$checksum_csv){
                    
                    
                    $valore_da_inserire = round(CAST_TO_FLOAT($data[8],0),4);
                    
                    
                    $query = $sql = 'UPDATE `retegas_dettaglio_ordini` SET `qta_arr` = \''.$valore_da_inserire.'\' WHERE `retegas_dettaglio_ordini`.`id_dettaglio_ordini` = '.$id_riga_dettaglio.' LIMIT 1;';
                    $res = $db->sql_query($query);
                    ridistribuisci_quantita_amici_1($id_riga_dettaglio,$valore_da_inserire,$rep);
                    
                    $t .="<tr>";
                    $t .="<td>$fullname</td>";
                    $t .="<td>$nomegas</td>";
                    $t .="<td>$codice_articolo</td>";
                    $t .="<td>$desc_art</td>";
                    $t .="<td>$qta_ord</td>";
                    $t .="<td><b>$valore_da_inserire</b></td>";
                    $t .="</tr>";
                    
                    $rep .="Riga $id_riga_dettaglio, $fullname : $codice_articolo $desc_art  Nuova qta: $valore_da_inserire<br>";
                    
                    
                }else{
                    $rep .="WARN Riga $id_riga_dettaglio CON CHECKSUM DIFF.<br>";
    
                }
             
             
             
         }else{
              $rep .="WARN Riga $id_riga_dettaglio NON corrispondente all'ordine $id_ordine<br>";
         }
 
     }
     $t .="</tbody>";
     $t .="</table>";
     
     
     
     $rep .= "<hr>Caricamento completato;<hr>";
     $rep .= "FINE CSV<hr>";
 }else{
     $rep .= "<hr>CARICAMENTO ABORTITO<hr>";
 }

 
 
 if($warning==0){
    $rep .= "<h3>RESULT : OK</h3>";
    
     
 }
   

//unlink($upload_dir."/".$file_name);
 
 //Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Conferma upload";


//Dico quale menù orizzontale dovrà  essere associato alla pagina.

    $r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts[]='<script type="text/javascript">                
                        $(document).ready(function() 
                            {
                                $("#output_1").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',                                                               
                                                        }); 
                                } 
                            );
</script>';


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}


//Questo è¨ il contenuto della pagina
$r->contenuto = "<div class=\"rg_widget rg_widget_helper\">".
                schedina_ordine($id_ordine).
                $msg.
                $t.
                rg_toggable("Visualizza REPORT importazione","report",$rep).
                "</div>";
               

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);