<?php
//Togliere quelli che non interessano
$_FUNCTION_LOADER=array("widgets",
                        "gphpcharts",
                        "swift",
                        "posta",
                        "amici",
                        "gas",
                        "listini",
                        "ditte",
                        "tipologie",
                        "articoli",
                        "graphics",
                        "ordini",
                        "ordini_valori",
                        "bacheca",
                        "geocoding",
                        "admin",
                        "dareavere",
                        "cassa",
                        "theming");
   
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

// TOTALE + ME STESSO
$totale_amici = $data[2]+1;
$colonna_del_totale = $data[2] + 4;
$amici_controllati = 0;  //Me stesso
$amici_aggiunti = 0;

$id_amico = array();
//ASSEGNO ANCHE il ME STESSO
$id_amico[3]=0;

for($i=4;$i<($totale_amici+3);$i++){
    $amici_controllati++;
    //Controllo che l'amico facca parte di user
    if(amici_referente_di_amico($data[$i])==_USER_ID){
        $amici_aggiunti++;
        $id_amico["$i"] = $data[$i];
        $lista_id .= $data[$i]." - ";     
    }else{
        $rep .= "<b class=\"ui-state-error\">Trovato amico $data[$i] non legato a Utente ordine</b><br>";
        $warning++;
    }
    
}



$lista_id = rtrim($lista_id,"- ");

$rep .= "Amici Trovati : $totale_amici<br>";
$rep .= "Amici Controllati : $amici_controllati<br>";
$rep .= "Amici OK : $amici_aggiunti ($lista_id) + Me stesso<br>";
    
if($amici_controllati<>($amici_aggiunti)){
    $warning++;
    $rep .="<b class=\"ui-state-error\">AMICI NON CORRISPONDENTI</b><br>";
}

   
 //SECONDA RIGA (intestazioni)  
 $data = fgetcsv($fd, 1000, "$separatore");
 $rep .= "Caricata riga Titoli<br>";
 // 0 = Cod art GAs, 1 = Articolo, 2 = Descrizione, 3 = mestesso, 4... n amici, n+1 = totale riga
 


 if($warning==0){
     
     //SI APRONO LE DANZE
     
     
     
     //CANCELLO VECCHI VALORI ORDINE
     if($do=="insert_db"){
              do_delete_all_ordine_user($id_ordine,_USER_ID);
              $rep .="VECCHI IMPORTI CANCELLATI !<br>";
     }
     
     
     
     $riga=2;
     while (($data = fgetcsv($fd, 1000, "$separatore")) !== FALSE) {
         $riga++;
         
         unset($somma_riga);
         //Iterazione all'interno di DATA 
         
         if(CAST_TO_FLOAT($data[$colonna_del_totale],0)==0){
             //$rep .="<hr>Riga #$riga<br>";
             //$rep .= "Codice ".$data[0]." Nessuna richiesta.<br>";    
         
         //SE IL TOTALE NON E' ZERO
         }else{
             //$rep .="<hr>Riga #$riga<br>";
             $rep .= "<hr>Riga #$riga - Codice ".$data[0]." RICHIESTO TOT.".$data[$colonna_del_totale]."<br>";
            
            
            //SOMMA COLONNE AMICI
            while (list($key, $val) = each($data)) {
                //echo "$key => $val<br>";
                    //GUARDO DA ME STESSO FINO ALLA RIGA PRIMA DEL TOTALE
                    if($key>2 and $key<$colonna_del_totale){
                        $somma_riga = $somma_riga + CAST_TO_FLOAT($val,0);
                        
                    }
            }
            
            if(round(CAST_TO_FLOAT($data[$colonna_del_totale],0),4)==round(CAST_TO_FLOAT($somma_riga,0),4)){
                $rep .= "Somma delle singole voci riga: OK<br>";
                
               
            
                //Controllo che l'articolo faccia parte dell'ordine
                //$data[0] e $Id_ordine
                
                if(articolo_id_listino($data[0])==listino_ordine_from_id_ordine($id_ordine)){
                    $rep .= "Articolo appartenente al listino: OK<br>";
        
                
                
                    //Controllo somma se è compatibile con Qmin
                    
                    $q_multi =round(db_val_q("id_articoli",$data[0],"qta_minima","retegas_articoli"),4); 
                    if(is_multiplo($q_multi,round($somma_riga,4))){
                         $rep .= "Check multiplo ($q_multi con $somma_riga) OK<br>";
     
                         $prezzo = articolo_suo_prezzo($data[0]);
                            
                         $euro_totale_riga = round($prezzo * $data[$colonna_del_totale],4);
                         $euro_totale_ordine = $euro_totale_ordine + $euro_totale_riga;
                          
                         $rep .= "Prezzo: $prezzo, Valore riga: $euro_totale_riga<br>";
     
                        //Controllo se con la cassa sono OK
                   
                        //Inserisco DETTAGLIO_ORDINE con somma riga
                        $rep .= "INS DETTAGLIO Ord : Ordine : $id_ordine, Q_ord: $somma_riga, User : "._USER_ID."<br>";
                        
                        if($do=="insert_db"){
                                    
                                    $query_inserimento_articolo = "INSERT INTO retegas_dettaglio_ordini ( 
                                                                    id_utenti,
                                                                    id_articoli,             
                                                                    data_inserimento,
                                                                    qta_ord,
                                                                    id_amico,
                                                                    id_ordine,
                                                                    qta_arr) 
                                                                    VALUES (
                                                                        '"._USER_ID."',
                                                                        '".$data[0]."',
                                                                        NOW(),
                                                                        '$somma_riga',
                                                                        '0',
                                                                        '$id_ordine',
                                                                        '$somma_riga'
                                                                        );";

                                    $result = $db->sql_query($query_inserimento_articolo);
                                    $mail_necessaria = "SI";

                                    // scopro qual'è l'ultimo ID inserito (RIGA Dettaglio_ordine)

                                    $res_l = mysql_query("SELECT LAST_INSERT_ID();");
                                    $row_l = mysql_fetch_array($res_l);
                                    $last_id=$row_l[0];
                                    usleep(50);
                            
                            
                                    //QUERY INSERT !!! DETTAGLIO
                                    $rep .="DB DETTAGLIO INSERTED ! (id_riga = $last_id)<br>";
                        }
                        //E scopro ID della riga creata
                        
                        
                        
                    
                        //CICLO CON OGNI AMICO
                        reset($data);
                        while (list($key, $val) = each($data)) {
                            //echo "$key => $val<br>";
                            //GUARDO DA ME STESSO FINO ALLA RIGA PRIMA DEL TOTALE
                            if($key>2 and $key<$colonna_del_totale){
                            
                            
                            $amico_attuale = $key;
                            
                            $id_amico_attuale = $id_amico[$key];
                            
                            $importo_attuale = $val;
                            
                            //echo "AMICO Controllato : $amico_attuale <br>";


                            if ($importo_attuale>0){
                                //Inserisco Distribuzione spesa con i dettagli
                                $rep .="INS DISTRIBUZIONE Ord con Id_amico $id_amico_attuale e Q_ord: $importo_attuale<br>";
                                
                                if($do=="insert_db"){
                                    
                                    $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                             id_riga_dettaglio_ordine,
                                                             id_amico,             
                                                             qta_ord,
                                                             qta_arr,
                                                             data_ins,
                                                             id_articoli,
                                                             id_user,
                                                             id_ordine) 
                                                             VALUES (
                                                                        '$last_id',
                                                                        '$id_amico_attuale',
                                                                        '$importo_attuale',
                                                                        '$importo_attuale',
                                                                         NOW(),
                                                                        '".$data[0]."',
                                                                        '"._USER_ID."',
                                                                        '$id_ordine'
                                                                        );";

                                    $result_dettaglio_spesa = $db->sql_query($query_distribuzione_spesa);
                                    usleep(50);
                                    
                                    //QUERY INSERT !!! DISTRIBUZIONE
                                    
                                    $rep .="DB DISTRIBUZIONE INSERTED !<br>";
                                
                                
                                
                                }    
                                
                            }
                            
                            
                        
                        }
                    }
                    
                    
                    
                    
                    
                
                    }else{
                        $rep .= "<b class=\"ui-state-error\">Articolo $data[0] MULTIPLO NON OK ($q_multi con $somma_riga)</b><br>";
                        $warning++;
                    }     
                
                
                }else{
                   $rep .= "<b>Articolo $data[0] NON APPARTENENTE ALL'ORDINE $id_ordine<br>";
                        $warning++; 
                }
                
                
                
                
                
                //OK SOM    
            }else{
                $rep .= "<b class=\"ui-state-error\">Articolo $data[0] SOMMA RIGA  $somma_riga <> TOTALE ".$data[$colonna_del_totale]."</b><br>";
                $warning++;
            }
            
         }
     }
     
     
     
     
     
     $rep .= "<hr>Totale ordine : $euro_totale_ordine<hr>";
     $rep .= "FINE CSV<hr>";
 }else{
     $rep .= "<hr>CARICAMENTO ABORTITO<hr>";
 }

 
 
 if($warning==0){
    $rep .= "<h3>RESULT : OK</h3>";
    
    //Valore ordine attuale
    $vo = valore_totale_mio_ordine($id_ordine,_USER_ID);
    //Cassa attuale
    $vc = cassa_utente_tutti_movimenti(_USER_ID);
    //Valore cassa senza ordine attuale
    $vrim = $vc+$vo;
    
    $va =  round((($euro_totale_ordine/100)* _GAS_COPERTURA_CASSA ) + $euro_totale_ordine);
    
        
    if($do=="insert_db"){
                // SE SNO QUA PER LA SECONDA VOLTA
                
                
                //MANDA LA MAIL
                if($mail_necessaria=="SI"){
                    rompi_le_balle($id_ordine,_USER_ID);
                }
                
                //UPDATE CASSA UTENTE SU MOVIMENTI NETTI   
                if(_USER_USA_CASSA){           
                 cassa_update_ordine_utente($id_ordine,_USER_ID);
                 $rep .= "<hr>Eseguito UPDATE su Cassa"; 
                }
                // CANCELLO IL FILE DI IMPORTAZIONE
                unlink($upload_dir."/".$file_name);
                //SCRIVO COSA HO FATTO
                log_me($id_ordine,_USER_ID,"ORD","MSS","Inserito ordine massivo",$euro_totale_ordine,$rep);
                //ME NE VADO
                go("ordini_mia_spesa_dettaglio",_USER_ID,"Controlla che tutto corrisponda","?id_ordine=$id_ordine");
                break;
         }else{
         
   
             //TUTTO OK
             $msg = "<div class=\"ui-state-highlight padding_6px ui-corner-all\">
                <h3>CONGRATULAZIONI</h3>
                <p>Sembra che sia tutto in regola.<br>
                Il tuo ordine attuale è di "._nf($vo)." Eu., quello importato ora di "._nf($euro_totale_ordine)." Eu.</p>
                <p>Per rendere tutto ciò effettivo, clicca per confermare qua sotto.<br>
                Ricorda che questo ordine va a sostituire tutti gli importi caricati in precedenza.<br>
                Qua sotto puoi vedere un dettaglio (Tecnico) dell'operazione che stai per fare.</p>
                <form method=\"POST\" action=\"\">
                <input type=\"hidden\" name=\"file_to_load\" value=\"$file_name\">
                <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
                <input type=\"hidden\" name=\"do\" value=\"insert_db\">
                <input type=\"submit\" class=\" awesome green large\" value=\"CONFERMA\">
                </form>
                </div>";
             
             //MA.......
             if(_USER_USA_CASSA){    
                 if(($vc-$va)< _GAS_CASSA_MIN_LEVEL){      //SE SONO SOTTO    
                    $rep .= "<h3>RESULT : NON OK - Importo cassa $vc, ordine aggiunto di anticipo copertura: "._nf($va)."</h3>";
                    //PASSO SOPRA A MSG
                    $msg = "<div class=\"ui-state-error padding_6px ui-corner-all\">
                        <h3>MANNAGGIA !!</h3>
                        <p>Credito insufficiente per questo acquisto;<br>
                        Ricorda che è contemplato un 10% di spese accessorie che vanno a sommarsi all'importo dell'ordine.<br>
                        Vi è inoltre una soglia minima di "._GAS_CASSA_MIN_LEVEL." Eu. (decisa dal tuo GAS) sotto la quale non si può ordinare.<br>
                        I totali effettivi saranno modificati o confermati ad ordine chiuso dal gestore o dal cassiere.</p>
                        <a class=\"awesome red\">Torna indietro</a>
                        </div>";  
                 }
             }                 
                
                  
         }
         
         
  //   }  SE SONO SOTTO  
    
    
    
    
    
    
 }else{
    $rep .= "<h3>RESULT : NON OK</h3>";
    $msg = "<div class=\"ui-state-error padding_6px ui-corner-all\">
            <h3>MANNAGGIA !!</h3>
            <p>Sono stati riscontrati alcuni errori nel file di importazione. E' necessario correggerli e ripetere l'operazione. 
            Forse ti può essere d'aiuto il Report (qua sotto) generato durante l'importazione. Gli errori sono evidenziati in rosso</p>
            <a class=\"awesome red\">Torna indietro</a>
            </div>"; 
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
//$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}


//Questo è¨ il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).
                $msg.
                rg_toggable("Visualizza REPORT importazione","report",$rep)
                ;
               

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)
 
 
   
?>     