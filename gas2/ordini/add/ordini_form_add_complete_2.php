<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_creare_ordini)){
     go("sommario",_USER_ID,"Non puoi Creare ordini.");
}

if($do=="add"){
        // controllo tutti i dati
        if(empty($descrizione) | $descrizione==""){
            $empty++;
            $msg .= "Descrizione mancante<br>";
        }


        //se manca la data di apertura apro l'ordine ora;
        if(empty($data_apertura) | $data_apertura==""){
            //$data_apertura=date("d/m/Y H:i");
            
            //POSTICIPO DI DUEE ORE L'APERTURA
            $date_now  = date( "d/m/Y H:i" ); 
            $time_now  = time( $date_now ); 
            $time_next = $time_now + 2 * 60 * 60; 
            $data_apertura = date( "d/m/Y H:i", $time_next);  
        }
        //se la data di apertura è passata la imposto come oggi
        if(gas_mktime($data_apertura)<gas_mktime(date("d/m/Y H:i"))){
            //$data_apertura=date("d/m/Y H:i");
            
            //POSTICIPO DI DUEE ORE L'APERTURA
            $date_now  = date( "d/m/Y H:i" ); 
            $time_now  = time( $date_now ); 
            $time_next = $time_now + 2 * 60 * 60; 
            $data_apertura = date( "d/m/Y H:i", $time_next);  
        }


        //se manca la data di chiusura la metto dopo due settimana quella di aperuta  

        if(empty($data_chiusura) | $data_chiusura==""){

            $data_chiusura=$data_apertura;
            //echo $data_chiusura."<br>";
            $data_chiusura = gas_mktime($data_chiusura) + (60*60*24*15);
            //echo $data_chiusura."<br>";
            $data_chiusura = date("d/m/Y",$data_chiusura);
            //echo $data_chiusura."<br>";
            $data_chiusura = $data_chiusura ." 22:00";
            //echo $data_chiusura;

        }    

        //se la data di apertura ? minore di quella di adesso do errore
        if(gas_mktime($data_apertura)<gas_mktime(date("d/m/Y H:i"))){
            $logical++;
            $msg .= "Data di chiusura ordine antecedente a quella di apertura<br>";
        } 

        //se l'ordine ? pi? lungo di 15 giorni do errore
        //if((gas_mktime($data_chiusura)-gas_mktime($data_apertura)) > (60*60*24*15)){
        //    $logical++;
        //    $msg .= "L'ordine non pu? rimanere aperto pi? di 2 settimane<br>";
        //}
        
        //se l'ordine ? pi? lungo di 15 giorni do errore
        if((gas_mktime($data_chiusura)<gas_mktime($data_apertura))){
            $logical++;
            $msg .= "La data di chiusura non può essere antecedente a quella di apertura. Strano, no ? <br>";
        } 


        //listino    
        if(empty($id_listino) | $id_listino=="" | $id_listino ==0){
            $empty++;
            $msg .= "Listino mancante o non valido<br>";
        }    

        // se manca il costo trasporto lo metto a zero 
        if(empty($costo_trasporto) | $costo_trasporto==""){
            $costo_trasporto = 0;
        }

        // se non ? riconosciuto do errore
        if(!valuta_valida(trim($costo_trasporto))){
            $logical++;
            $msg .= "Valore del costo di trasporto  non riconosciuto<br>";    
        }

        // se manca il costo gestione lo metto a zero 
        if(empty($costo_gestione) | $costo_gestione==""){
            $costo_gestione = 0;
        }

        if(!valuta_valida(trim($costo_gestione))){
            $logical++;
            $msg .= "Valore del costo di gestione  non riconosciuto<br>";    
        }

        // se manca il costo trasporto lo metto a zero 
        if(empty($costo_mio_gas) | $costo_mio_gas==""){
            $costo_mio_gas = 0;
        }

        // se non ? riconosciuto do errore
        if(!valuta_valida(trim($costo_mio_gas))){
            $logical++;
            $msg .= "Valore del costo del mio GAS non riconosciuto<br>";    
        }

        // se manca la percentuale lo metto a zero 
        if(empty($percentuale_mio_gas) | $percentuale_mio_gas==""){
            $percentuale_mio_gas = 0;
        }

        // se non ? riconosciuto do errore
        if(!percentuale_valida(trim($percentuale_mio_gas))){
            $logical++;
            $msg .= "Percentuale aggiuntiva del mio Gas non riconosciuta<br>";    
        }


        //NON DATA VALIDA -------------------------------------
        if(!controllodataora($data_chiusura)){
            $logical++;
            $msg .= "Data chiusura non valida<br>"; 
        }
        if(!controllodataora($data_apertura)){
            $logical++;
            $msg .= "Data apertura non valida<br>"; 
        }



        if($solo_cassati=="si"){
            $solo_cassati="si";
        }else{
            $solo_cassati="no";
        }




        //-------------------------- PRESO DA ORDINI ADD
        $e_total = $empty + $logical;

        if($e_total==0){

            //echo "ZERO ERRORI !!!";
            $data_2 = (int)$id_listino;
            $data_4 = sanitize(trim($descrizione));

            //POSTICIPO DI DUEE ORE L'APERTURA
            $date_now  = date( "d/m/Y H:i" );
            //echo "Date_now $date_now  <br>";  
            
            $time_now  = time($date_now);
            //echo "Time_now $time_now  <br>";
            
            //echo "Date_apertura $data_apertura  <br>";
            
            $time_apertura = time($data_apertura);
            //echo "Time_apertura $time_apertura  <br>";
            
            if((($time_apertura - $time_now)) < (2*60*60)){
                //echo "Time_apertura diff (".($time_apertura - $time_now).")  <br>";
                $time_next = $time_now + 2 * 60 * 60;
    
                //echo "Time_next $time_next  <br>";    
            }else{
                $time_next = $time_apertura;
            } 
             
            $date_next = date($data_apertura, $time_next);
            //echo "date_next $date_next  <br>";
            
            $data_7 = conv_date_to_db($date_next);
            
            
            $data_8 = conv_date_to_db($data_chiusura);
            $data_10 = $costo_trasporto;        // costo trasporto fisso
            $data_11 = $costo_gestione;
            $data_13=0;
            $data_14=0;
            $data_15=0;
            $data_16=1; // STATO COMUNQUE FUTURO
            $data_17=0;  
            $data_18=(int)$quanto_comunica;
            $data_19=sanitize($note_ordine);
            // QUERY INSERT
            $my_query="INSERT INTO retegas_ordini 
            (id_listini, 
            id_utente, 
            descrizione_ordini, 
            data_chiusura, 
            costo_trasporto, 
            costo_gestione, 
            min_articoli, 
            min_scatola, 
            privato, 
            data_apertura,
            id_stato,
            senza_prezzo,
            mail_level,
            note_ordini,
            solo_cassati)
            VALUES
            ('$data_2',
            '"._USER_ID."',
            '$data_4',
            '$data_8',
            '$data_10',
            '$data_11',
            '$data_14',
            '$data_15',
            '$data_13',
            '$data_7',
            '$data_16',
            '$data_17',
            '$data_18',
            '$data_19',
            '$solo_cassati');";

            //INSERT BEGIN ---------------------------------------------------------
            $result = $db->sql_query($my_query);
            if (is_null($result)){
                $msg .= "Errore nell'inserimento del record";
;  
            }else{

                // se l'ordine ? stato inserito, allora inserisco anche le referenze
                $res = mysql_query("SELECT LAST_INSERT_ID();");
                $row = mysql_fetch_array($res);
                $ur=$row[0];
                $gr = _USER_ID_GAS;

                // recupero le informazioni dal mio gas
                //$res_gas = $db->sql_query("SELECT * FROM retegas_gas WHERE id_gas='$gr'");
                //$row_gas = $db->sql_fetchrow($res_gas);


                $result = $db->sql_query("INSERT INTO retegas_referenze (id_ordine_referenze,
                id_utente_referenze,
                id_gas_referenze,
                note_referenza,
                maggiorazione_referenza,
                maggiorazione_percentuale_referenza)
                VALUES 
                ('$ur',
                '"._USER_ID."',
                '$gr',
                '".$testo_percentuale_mio_gas."',
                '".$costo_mio_gas."',
                '".$percentuale_mio_gas."');");
                // e poi vado ad aggiungere anche i gas coinvolti, ma con nessun referente


                while (list ($key,$val) = @each ($box)) { 
                    // Per ogni GAS indicato nel Box associo la sua percentuale di maggiorazione
                    $res_gas = $db->sql_query("SELECT * FROM retegas_gas WHERE id_gas='$val'");
                    $row_gas = $db->sql_fetchrow($res_gas);

                    $result = $db->sql_query("INSERT INTO retegas_referenze (id_ordine_referenze, id_utente_referenze, id_gas_referenze, note_referenza, maggiorazione_percentuale_referenza) "
                    ." VALUES ('$ur', '0', '$val', '".$row_gas["comunicazione_referenti"]."', '".$row_gas["maggiorazione_ordini"]."');");                        

                }        
                // referenze
                $msg .= "Ordine $ur ($descrizione) PARTITO !!";
                $nome_ordine = descrizione_ordine_from_id_ordine($ur);
                $messa = "<b>L'utente $fullname ha creato l'ordine complete $nome_ordine</b>";
                log_me($ur,_USER_ID,"ORD","MOD",$messa,0,$my_query);
                go("sommario",_USER_ID,$msg);
            }

        }else{

            unset($do);    
            $msg .= "Controlla i dati e riprova<br>";
        }
        //-------------------------- PRESO DA ORDINI ADD
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Nuovo ordine (Completo)";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_completo($user);;

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_select2();
$r->javascripts_header[]=java_head_datetimepicker();
$r->javascripts_header[]=java_head_ckeditor();

$r->javascripts[]=java_tablesorter("gas_table");
$r->javascripts[] = "<script>
                        $(document).ready(function() { $('#selection').select2({
                                                                            matcher: function(term, text, opt) {
                                                                            //console.log(String(opt.attr(\"alt\")).toUpperCase());
                                                                            return text.toUpperCase().indexOf(term.toUpperCase())>=0
                                                                            ||
                                                                            String(opt.attr(\"alt\")).toUpperCase().indexOf(term.toUpperCase())>=0}
                                                                        }); 
                        });
                        </script>";
$r->javascripts[] = c1_ext_javascript_datetimepicker("#datainizio");                        
$r->javascripts[] = c1_ext_javascript_datetimepicker("#datafine");
 

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
if($msg){$r->messaggio=$msg;}
//Contenuto
        
        
        if(isset($id_listino)){
            $id_ditta = ditta_id_from_listino($id_listino);
            $script_listini='';
        }

        $query_ditte = "SELECT *
        FROM
        retegas_ditte
        Inner Join retegas_listini ON retegas_ditte.id_ditte = retegas_listini.id_ditte
        WHERE
        retegas_listini.data_valido >  now()
        AND
        retegas_listini.tipo_listino = 0
        GROUP BY
        retegas_ditte.descrizione_ditte";
        $res_ditte = $db->sql_query($query_ditte);

        while ($row = $db->sql_fetchrow($res_ditte)){

            //CONSTRUCTOR DITTA
            $l .= '<optgroup label="'.$row["descrizione_ditte"].'">';
        
        
            //SCELGO TRA I LISTINI DELLA DITTA NON SCADUTI E NON MAGAZZINO
            $query_listini = "SELECT * FROM retegas_listini 
                                WHERE id_ditte='".$row["id_ditte"]."'
                                AND data_valido > NOW()
                                AND tipo_listino = 0";
            $res_listini = $db->sql_query($query_listini);
            while ($row_listini = $db->sql_fetchrow($res_listini)){
                
                
                
                $add = "NO";
                
                if($row_listini["is_privato"]>0){
                    if(id_gas_user($row_listini["id_utenti"]<>_USER_ID_GAS)){
                       $add = "NO"; 
                    }else{
                       $add = "SI"; 
                    }
                }else{
                    $add = "SI";
                }
                //sanitize($row["descrizione_ditte"])
                
                if($row_listini["id_listini"]==$id_listino){
                    $selected=" SELECTED ";
                }else{
                    $selected ="";
                }
                
                if($add=="SI"){
                    $l .= '<option '.$selected.' alt="'.sanitize($row["descrizione_ditte"]).'" value="'.$row_listini["id_listini"].'">'.$row_listini["descrizione_listini"].' ('.fullname_from_id($row_listini["id_utenti"]).')</option>';
                }
            }
        
            $l .="</optgroup>";
        }
        //TABELLA GAS PARTECIPANTI

        while (list ($key,$val) = @each ($box)) { 
            // Per ogni GAS indicato nel Box associo la sua percentuale di maggiorazione
            if(isset($box[$key])){
                //echo "BOX = ".$box[$val]."<br>";
                $box_selected[$val]=" checked=\"yes\" ";
                //echo "BOXSELECTED = ".$box_selected[$val]."<br>";
            }

        }


        $my_gas_lat = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lat","retegas_gas");
        $my_gas_lng = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lng","retegas_gas");

        $h_table ="
        <div id=\"gas_table_container\" class=\"rg_widget rg_widget_helper\" style=\"height:18em;overflow-y:auto\">
        <table id=\"gas_table\" >
        <thead>
            <th>GAS</th>
            <th>DES</th>
            <th>Utenti attivi attualmente / Utenti totali</th>
            <th data-sorter=\"numeric\" data-sortinitialorder=\"asc\">Distanza</th>
            <th>&nbsp;</th>
        </thead>
        <tbody>
        ";

        $result = $db->sql_query("SELECT * FROM retegas_gas;");             
        while ($row = $db->sql_fetchrow($result)){
            $riga++;
            $gas = $row["descrizione_gas"];
            $id_gas = $row["id_gas"];
            $id_des = $row["id_des"];
            $ute = gas_n_user_Act($id_gas);
            $utot = gas_n_user($id_gas);
            $des_nome = db_val_q("id_des",$row["id_des"],"des_descrizione","retegas_des");
            $other_gas_lat = db_val_q("id_gas",$id_gas,"gas_gc_lat","retegas_gas");
            $other_gas_lng = db_val_q("id_gas",$id_gas,"gas_gc_lng","retegas_gas");
            $distanza = round(getDistanceBetweenPointsNew($my_gas_lat,$my_gas_lng,$other_gas_lat,$other_gas_lng),1);
            
            
            if ($id_des>0){ //non deve essere IL DES DI SERVIZIO
            
                if (_USER_ID_GAS<>$id_gas){ // IL DES DIVERSO DAL PROPRIO 
                    
                    //$gas_ext_perm = leggi_permessi_gas($id_gas);
                    $gas_ext_perm = read_option_gas_text($id_gas,"_GAS_PUO_PART_ORD_EST");
                    
                    if($gas_ext_perm=="SI"){
                        $condizione = "<input type=\"checkbox\" name=box[] value=\"$id_gas\" ".$box_selected[$id_gas].">";
                    }else{
                        $condizione = "Condivisione ordine non possibile";
                    }
                    $h_table .="
                    <tr>
                    <td>$gas</td>
                    <td>$des_nome</td>
                    <td>$ute / $utot</td >
                    <td>$distanza Km</td>
                    <td>$condizione</td>
                    </tr>
                    ";
                    
                }
            }    
        }

        $h_table .="</tbody></table>
        </div>
        "; 

        //CONTROLLO SE IL GAS PUO' PROPORRE ORDINI AGLI ALTRI
        if(read_option_gas_text_new(_USER_ID_GAS,"_GAS_PUO_COND_ORD_EST")=="NO"){
            $h_table = "<strong style=\"text-align:center;\">Il tuo GAS non può momentaneamente condividere ordini con gli altri</strong>"; 
        }
        

        // recupero le informazioni dal mio gas
        $res_gas = $db->sql_query("SELECT * FROM retegas_gas WHERE id_gas='"._USER_ID_GAS."'");
        $row_gas = $db->sql_fetchrow($res_gas);
        if (!isset($testo_percentuale_mio_gas)){
            $testo_percentuale_mio_gas = $row_gas["comunicazione_referenti"];    
        }
        if (!isset($percentuale_mio_gas)){
            $percentuale_mio_gas = $row_gas["maggiorazione_ordini"];    
        }  

        //Guarda se partecipano i solo cassati
        if(read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_DEFAULT_SOLO_CASSATI")=="SI"){
           $solo_cassati_checked="CHECKED"; 
        }else{
           $solo_cassati_checked=""; 
        }
        
        

        // HELPS
        $help_ditta = '<b>Scegli il listino</b> : Viene proposto l\'intero elenco delle ditte disponibili, con almeno un listino valido. Usare il campo di ricerca per filtrare tutti i listini presenti, immettendo ad esempio il nome della ditta o qualche parola che la identifica.';
        $help_descrizione ='Questo è il nome che verrà poi usato per rappresentare l\'ordine';
        $help_data_apertura ='La data e l\'ora di apertura ordine<br><b>NB :</b>Se manca o è antecedente all\'ora attuale, verrà presa l\'ora attuale;';
        $help_data_chiusura ='La data e l\'ora di chiusura ordine<br><b>NB :</b>Se manca verrà considerato che l\'ordine chiude trascorse due settimane dalla sua apertura, alle ore 22.00;';
        $help_costo_trasporto ='Le spese di trasporto dell\'ordine. Questo valore si può modificare successivamente, sia ad ordine aperto che ad ordine chiuso.<br>Le spese di trasporto sono ripartizionate proporzionalmente alla spesa effettuata da ogni singolo utente. (di tutti i GAS).<br>Se viene lasciato vuoto si assume che siano 0;';
        $help_costo_gestione='Le spese di gestione dell\'ordine. Questo valore si può modificare successivamente, anche ad ordine chiuso. Anche le spese di gestione sono ripartizionate proporzionalmente alla spesa effettuata da ogni singolo utente. (di tutti i GAS). Se viene lasciato vuoto si assume che siano 0;';
        $help_note_ordine ='In questo piccolo editor è possibile scrivere delle note che verranno mostrate in calce alla scheda dell\'ordine. Se si fa il copia e incolla da un\'altrea pagina WEB, è possibile includere anche delle immagini';
        $help_costo_mio_gas ='In questo campo vegnono indicati i costi relativi SOLTANTO AL GAS DI APPARTENENZA di chi propone l\'ordine. Verranno ripartiti proporzionalmente in base alla spesa di ogni utente. Ogni GAS, tramite il referente GAS ha la possibilità di inserire dei costi personalizzati, personalizzabili per ogni ordine gestito.';  
        $help_percentuale_mio_gas =' Il responsabile del tuo GAS, ha indicato nella scheda relativa al tuo GAS una Maggiorazione percentuale che viene applicata su ogni ordine, (che coinvolge solo il proprio GAS). In questo campo viene già proposta questa cifra, ma è possibile modificarla a piacimento, previo accordo con il resto del proprio GAS. Qeesta maggiorazione sarà applicata al netto della merce acquistata di ogni singolo utente.';  
        $help_testo_percentuale ='Breve descrizione del motivo della maggiorazione percentuale sopra descritta, proposta dal responsabile del proprio gas ma qui liberamente modificabile.';
        $help_comunicazioni =' Il sito ReteGas è in grado di inviarti degli aggiornamenti tramite mail su quanto sta accadendo nel tuo ordine. In base al livello che selezioni potrai non essere disturbato affatto, ricevere solo gli avvisi importanti oppure essere aggiornato su ogni movimentazione avvenuta sull\'ordine che stai gestendo.';
        $help_gas_partecipanti ='In questa tabella sono presenti i gas iscritti a ReteGas.AP, con il numero dei relativi iscritti. Spuntando le caselle al loro fianco puoi decidere se dare la possibilità anche a loro di partecipare al tuo ordine. Per partecipare, gli altri GAS dovranno avere un referente GAS. Finchè un gas esterno non ha un referente GAS è possibile revocare la condivisione. Le condivisioni possono essere aggiunte anche in un secondo momento, anche ad ordine già aperto.';
        $help_solo_cassati ='Permetti agli utenti di partecipare solo se hanno credito disponibile.';


        //TABELLA GAS PARTECIPANTI




        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Crea un nuovo ordine : procedura completa</h3>
        
        <div class="rg_widget rg_widget_helper ui-state-error">
        <h3>ATTENZIONE !</h3>
        <p>Questa pagina è stata modificata di recente, si prega di controllare se l\'ordine (e i suoi parametri) vengono inseriti correttamente.</p>
        <p>Grazie</p>
        </div>

        <form name="Ordine_completo_v2" method="POST" action="" class="retegas_form">

        <div>
        <h4>1</h4>
        <span>
        <label for="selection">Scegli il listino da utilizzare</label>
        <select id="selection" name="id_listino" style="width:50%">
        <option value="0">Nessuna ditta e listino selezionati</OPTION>
        '.$l.'        
        </select>
        <h5 title="'.$help_ditta.'">Inf.</h5>
        </span>

        </div>


        <div class="form_box">
        <h4>2</h4>
        <label for="descrizione">poi dai un nome all\'ordine</label>
        <input type="text" name="descrizione" value="'.$descrizione.'" size="50"></input>
        <h5 title="'.$help_descrizione.'">Inf.</h5>
        </div>

        <div>
        <h4>3</h4>
        <label for="datainizio">...decidi quando apre...</label>
        <input id="datainizio" type="text" name="data_apertura" value="'.$data_apertura.'" size="20"></input>
        <h5 title="'.$help_data_apertura.'">Inf.</h5>
        </div>

        <div>
        <h4>4</h4>
        <label for="datafine">...decidi quando chiude...</label>
        <input id="datafine" type="text" name="data_chiusura" value="'.$data_chiusura.'" size="20"></input>
        <h5 title="'.$help_data_chiusura.'">Inf.</h5> 
        </div>

        <div>
        <h4>5</h4>
        <label for="costo_trasporto">Inserisci le spese di trasporto</label>
        <input style="text-align:right;" id="costo_trasporto" type="text" name="costo_trasporto" value="'.$costo_trasporto.'" size="10"></input>
        <h5 title="'.$help_costo_trasporto.'">Inf.</h5>
        </div>

        <div>
        <h4>6</h4>
        <label for="costo_gestione">...e quelle di gestione;</label>
        <input style="text-align:right;" id="costo_gestione" type="text" name="costo_gestione" value="'.$costo_gestione.'" size="10"></input>
        <h5 title="'.$help_costo_gestione.'">Inf.</h5>
        </div>


        <div>
        <h4>7</h4>
        <label for="costo_mio_gas">Qua inserisci i costi RELATIVI AL TUO GAS;</label>
        <input style="text-align:right;" id="costo_mio_gas" type="text" name="costo_mio_gas" value="'.$costo_mio_gas.'" size="10"></input>
        <h5 title="'.$help_costo_mio_gas.'">Inf.</h5>
        </div>

        <div>
        <h4>8</h4>
        <label for="percentuale_mio_gas">Il tuo Gas ha come maggiorazione questa percentuale :</label>
        <input style="text-align:right;" id="percentuale_mio_gas" type="text" name="percentuale_mio_gas" value="'.$percentuale_mio_gas.'" size="10"></input> %
        <h5 title="'.$help_percentuale_mio_gas.'">Inf.</h5>
        </div>

        <div>
        <h4>9</h4>
        <label for="testo_percentuale_mio_gas">Riferita a questa motivazione :</label>
        <input id="testo_percentuale_mio_gas" type="text" name="testo_percentuale_mio_gas" value="'.$testo_percentuale_mio_gas.'" size="50"></input>
        <h5 title="'.$help_testo_percentuale.'">Inf.</h5>
        </div>

        <div>
        <h4>10</h4>
        <label for="note_ordine">Qua puoi mettere delle note che saranno visibili a tutti :</label>
        <h5 title="'.$help_note_ordine.'">Inf.</h5>
        <textarea id="note_ordine" class ="ckeditor" name="note_ordine" cols="28" style="display:inline-block;">'.$note_ordine.'</textarea>
        </div>


        <div>
        <h4>11</h4>
        <label for="quanto_comunica">..e qua decidi quanto vuoi essere aggiornato sul tuo ordine</label>
            <select name="quanto_comunica" id= "quanto_comunica">
            <option value="1" SELECTED >Normale (Consigliato)</option>
            <option value="0" >Nessuna comunicazione automatica</option>
            <option value="2" >Avvisami su ogni cosa che succede</option>
        </select>        
        <h5 title="'.$help_comunicazioni.'">Inf.</h5>
        </div>

        <div>
        <h4>12</h4>
        <label for="gas_partecipanti">Seleziona gli altri GAS che vuoi far partecipare a questo ordine :</label>
        <h5 title="'.$help_gas_partecipanti.'">Inf.</h5>
        '.$h_table.'
        </div>

        <div>
        <h4>13</h4>
        <label for="solo_cassati">Permetti la partecipazione esclusivamente a chi ha sufficiente credito disponibile.</label>
        <input id="solo_cassati" type="checkbox" name="solo_cassati" value="si" '.$solo_cassati_checked.'></input>
        <h5 title="'.$help_solo_cassati.'">Inf.</h5>
        </div>

        <div>
        <h4>14</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Fai partire l\'ordine" align="center" >
        <input type="hidden" name="do" value="add">
        </div>
        </form>
        <br>

        </div>';

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);