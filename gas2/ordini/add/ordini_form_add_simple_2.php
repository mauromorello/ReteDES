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
        
        $data_chiusura= $giorni_chiusura;
            
        //se manca la data di chiusura chiudo l'ordine tra una settimana alle 22:00;  
        if(empty($data_chiusura) | $data_chiusura==""){
            
            
            $data_chiusura=date("d/m/Y");
            //echo $data_chiusura."<br>";
            $data_chiusura = gas_mktime($data_chiusura) + (60*60*24*7);
            //echo $data_chiusura."<br>";
            $data_chiusura = date("d/m/Y",$data_chiusura);
            //echo $data_chiusura."<br>";
            $data_chiusura = $data_chiusura ." 22:00";
            //echo $data_chiusura;

        }else{            
           $data_chiusura=date("d/m/Y",gas_mktime(date("d/m/Y")) + (60 * 60 * 24 * $data_chiusura));
           $data_chiusura = $data_chiusura ." 22:00";  
        }    

        if(empty($id_listino) | $id_listino=="" | $id_listino ==0){
            $empty++;
            $msg .= "Listino mancante o non valido<br>";
        }    

        //NON DATA VALIDA -------------------------------------
        if(!controllodataora($data_chiusura)){
            $logical++;
            $msg .= "Data chiusura non valida<br>"; 
        }
       
        
        //-------------------------- PRESO DA ORDINI ADD
        $e_total = $empty + $logical;

        if($e_total==0){

            //echo "ZERO ERRORI !!!";
            $data_2 = (int)$id_listino;
            $data_4 = sanitize(trim($descrizione));
            
            //POSTICIPO DI DUEE ORE L'APERTURA
            $date_now  = date( "d/m/Y H:i" ); 
            $time_now  = time( $date_now ); 
            $time_next = $time_now + 2 * 60 * 60; 
            $date_next = date( "d/m/Y H:i", $time_next); 
            
            
            $data_7 = conv_date_to_db($date_next);
            $data_8 = conv_date_to_db($data_chiusura);
            
            // L'opzione SOLO CASSATI E' PRESA DAL DEFAULT DELLE OPZIONI CASSA
            $data_20=read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_DEFAULT_SOLO_CASSATI");

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
            '0',
            '0',
            '0',
            '0',
            '0',
            '$data_7',
            '1',
            '0',
            '1',
            '',
            '$data_20');";

            //INSERT BEGIN ---------------------------------------------------------
            $result = $db->sql_query($my_query);
            if (is_null($result)){
                $msg .= "Errore nell'inserimento del record";
                pussa_via();
                  
            }else{

                // se l'ordine ? stato inserito, allora inserisco anche le referenze
                $res = mysql_query("SELECT LAST_INSERT_ID();");
                $row = mysql_fetch_array($res);
                $ur=$row[0];
                $gr = _USER_ID_GAS;

                // recupero le informazioni dal mio gas
                $res_gas = $db->sql_query("SELECT * FROM retegas_gas WHERE id_gas='$gr'");
                $row_gas = $db->sql_fetchrow($res_gas);


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
                '".$row_gas["comunicazione_referenti"]."',
                '0',
                '".$row_gas["maggiorazione_ordini"]."');");

                
                $nome_ordine = descrizione_ordine_from_id_ordine($ur);
                $messa = "<b>L'utente $fullname ha creato l'ordine speedy $nome_ordine</b>";
                log_me($ur,_USER_ID,"ORD","CRE",$messa,0,$my_query);

                $msg .= "Ordine $ur ($descrizione) PARTITO !!";
                
                go("sommario",_USER_ID,$msg);
                
            }

        }else{

            unset($do);    
            $msg .= "Controlla i dati e riprova";
        }
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Nuovo ordine (semplice)";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_completo($user);;

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_select2();
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

        if(isset($data_chiusura)){
            $data_chiusura = CAST_TO_INT(gas_mktime($data_chiusura)-gas_mktime(date("d/m/Y"))/24/60/60,0,15);
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

        $help_descrizione='Inserisci una descrizione chiara e concisa dell\'ordine che stai aprendo.';
        $help_ditta ='Per velocizzare la ricerca del listino digita alcune lettere del nome della ditta o del nome del listino o del nome di chi lo ha creato per filtrare la lista.';
        $help_listino ='Seleziona un listino associato alla ditta scelta in precedenza tra quelli disponibili';
        $help_data_chiusura='Scegli tra quanti giorni l\'ordine deve chiudersi;<br>Se lasciato vuoto, si chiuderà tra una settimana alle 22.00;<br>Gli ordini aperti con questa scheda possono durare massimo 15 giorni.';
        $help_partenza = 'Una volta che l\'ordine è partito, potrai modificare tutti i dati che hai immesso e/o aggiungerne altri.<br>
        Puoi anche annullarlo, ma soltanto se nessuno ha prenotato articoli.';


        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Crea un nuovo ordine : procedura veloce</h3>

        <form name="Nuovo ordine Veloce" method="POST" action="" class="retegas_form">

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

        <div>
        <h4>2</h4>
        <label for="descrizione">poi dai un nome all\'ordine</label>
        <input type="text" name="descrizione" value="'.$descrizione.'" size="50"></input>
        <h5 title="'.$help_descrizione.'">Inf.</h5>
        </div>

        <div>
        <h4>3</h4>
        <label for="datetimepicker">...decidi tra quanti giorni chiude...</label>
        <input id="" type="text" name="giorni_chiusura" value="'.$giorni_chiusura.'" size="20"></input>
        <h5 title="'.$help_data_chiusura.'">Inf.</h5></div>

        <div>
        <h4>4</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Fai partire l\'ordine" align="center" >
        <input type="hidden" name="do" value="add">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        <br>
        <div class="ui-state-error ui-corner-all" style="padding:1em">NB : Usando questa procedura "rapida" l\'ordine sarà disponibile da subito, e potranno parteciparvi solo gli utenti del tuo gas.<br>
        potrai cambiare le impostazione e/o aggiungere altre informazioni dal menù "modifica" che troverai nella scheda specifica, oppure se vuoi
        inserire un ordine più complesso, usa la procedura completa. (Vedi Help)<br>
        Potrai cancellare questo ordine finchè non vi è nessun articolo prenotato.
        </div>
        </div>';

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);