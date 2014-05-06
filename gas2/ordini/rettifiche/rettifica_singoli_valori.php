<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

$mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);

//Se posso vedere tutti gli ordini
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){

    if ($mio_Stato<3){
        go("sommario",_USER_ID,"Questo ordine non mi compete");
    }

}


if($act=="deldett"){

    $id_utente=CAST_TO_INT($id_utente);

    if($id_utente>0){

        $pippo = "delete from retegas_dettaglio_ordini WHERE id_dettaglio_ordini='$id_dettaglio' LIMIT 1;";
        $db->sql_query($pippo);

        $pippo = "delete from retegas_distribuzione_spesa WHERE id_riga_dettaglio_ordine='$id_dettaglio';";
        $db->sql_query($pippo);

        if(read_option_text($id_utente,"_USER_USA_CASSA")=="SI"){
                cassa_update_ordine_utente($id_ordine,$id_utente);
        }

        log_me($id_ordine,_USER_ID,"ORD","DEL","Delete dettaglio $id_dettaglio di $id_utente",0,"Ordine : $id_ordine, dettaglio : $id_dettaglio");
    }
    die();
}

if($act=="add_article"){
    $e=0;

    $id_utente_select=CAST_TO_INT($id_utente_select);
    if($id_utente_select==0){
        $msg.="Utente non selezionato<br>";
        $e++;
    }

    $id_articolo_select=CAST_TO_INT($id_articolo_select);
    if($id_articolo_select==0){
        $msg.="Articolo non selezionato<br>";
        $e++;
    }

    $qta_arr= CAST_TO_FLOAT($quantita,0);
    if($qta_arr==0){
        $msg.="Quantità non impostata<br>";
        $e++;
    }

    if($e==0){  //--------------------------------------------------------

    $descrizione_attuale = sanitize(articolo_sua_descrizione($id_articolo_select));
    $codice_attuale = sanitize(articolo_suo_codice($id_articolo_select));
    $udm_attuale = sanitize(articolo_suo_udm($id_articolo_select));
    $prezzo = articolo_suo_prezzo($id_articolo_select);
    //INSERT
    $query_inserimento_articolo = "INSERT INTO retegas_dettaglio_ordini (
                                                    id_utenti,
                                                    id_articoli,
                                                    data_inserimento,
                                                    qta_ord,
                                                    id_amico,
                                                    id_ordine,
                                                    qta_arr,
                                                    prz_dett,
                                                    prz_dett_arr,
                                                    art_codice,
                                                    art_desc,
                                                    art_um)
                                                    VALUES (
                                                        '$id_utente_select',
                                                        '$id_articolo_select',
                                                        NOW(),
                                                        '$qta_arr',
                                                        '0',
                                                        '$id_ordine',
                                                        '$qta_arr',
                                                        '$prezzo',
                                                        '$prezzo',
                                                        '$codice_attuale',
                                                        '$descrizione_attuale',
                                                        '$udm_attuale'
                                                        );";
        $querona .= "INSERIMENTO ARTICOLO n. ".$id_articolo_select ."-->". $query_inserimento_articolo." <-- ";

        $result = $db->sql_query($query_inserimento_articolo);
        $mail_necessaria = "SI";

        // scopro qual'è l'ultimo ID inserito (RIGA Dettaglio_ordine)

        $res = mysql_query("SELECT LAST_INSERT_ID();");
        $row = mysql_fetch_array($res);
        $last_id=$row[0];

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
                                                        0,
                                                        '$qta_arr',
                                                        '$qta_arr',
                                                         NOW(),
                                                        '$id_articolo_select',
                                                        '$id_utente_select',
                                                        '$id_ordine'
                                                        );";

        $querona .= "DISTRIBUZIONE ARTICOLO n. ".$r ."-->". $query_distribuzione_spesa." <-- ";
        $result_dettaglio_spesa = $db->sql_query($query_distribuzione_spesa);

        if(read_option_text($id_utente_select,"_USER_USA_CASSA")=="SI"){
                $querona .= "<br>UPDATE CASSA<br>";
                cassa_update_ordine_utente($id_ordine,$id_utente_select);
        }else{
                $querona .= "<br>NO USER CASSA<br>";
        }

        log_me($id_ordine,_USER_ID,"ORD","RET","Modifica $id_ordine a $id_utente_select, aggiunto articolo $id_articolo_select",$vo,$querona);
    }


}


if($act=="qta_arr"){

    $qta_arr= CAST_TO_FLOAT($newvalue,0);

    if(isset($id_dettaglio)){
        $sql = "UPDATE retegas_dettaglio_ordini SET qta_arr='$qta_arr' WHERE id_dettaglio_ordini='$id_dettaglio' LIMIT 1;";
        $db->sql_query($sql);
        ridistribuisci_quantita_amici_1($id_dettaglio,$qta_arr);
    }
    echo number_format($qta_arr,4);
    die();
}

if($act=="prz_dett_arr"){

    $prz_qta_arr= CAST_TO_FLOAT($newvalue,0);

    if(isset($id_dettaglio)){
        $sql = "UPDATE retegas_dettaglio_ordini SET prz_dett_arr='$prz_qta_arr' WHERE id_dettaglio_ordini='$id_dettaglio' LIMIT 1;";
        $db->sql_query($sql);

    }
    echo number_format($prz_qta_arr,4);
    die();
}

if($act=="descrizione"){

    $desc= sanitize(CAST_TO_STRING($newvalue));

    if(isset($id_dettaglio)){
        $sql = "UPDATE retegas_dettaglio_ordini SET art_desc='$desc' WHERE id_dettaglio_ordini='$id_dettaglio' LIMIT 1;";
        $db->sql_query($sql);

    }
    echo $newvalue;
    die();
}

if($act=="codice"){

    $cod= sanitize(CAST_TO_STRING($newvalue));

    if(isset($id_dettaglio)){
        $sql = "UPDATE retegas_dettaglio_ordini SET art_codice='$cod' WHERE id_dettaglio_ordini='$id_dettaglio' LIMIT 1;";
        $db->sql_query($sql);

    }
    echo $newvalue;
    die();
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Rettifica singoli valori";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_jeditable();
$r->javascripts_header[]=java_head_select2();
$r->javascripts_header[]="<script type=\"text/javascript\" src=\"".$RG_addr["js_jcalc"]."\"></script>\n";
$r->javascripts[]="<script type=\"text/javascript\">
                    $(document).ready(function() {

                        $('#id_utente_select').select2();
                        $('#id_articolo_select').select2();

                        $('.cancett').click(function(e){

                                    var id_dettaglio = $(this).data('id_dettaglio');
                                    var id_utente = $(this).data('id_utente');

                                    if (confirm('Sicuro di cancellare ?')) {
                                        $.post( 'rettifica_singoli_valori.php?id_ordine=".$id_ordine."&act=deldett&id_dettaglio='+id_dettaglio+'&id_utente='+id_utente, function( data ) {
                                            window.location.href='rettifica_singoli_valori.php?id_ordine=".$id_ordine."';

                                        });

                                    } else {

                                    }

                                    return false;
                        });

                        $('#output_1').tablesorter({widgets: ['zebra','filter','saveSort'],
                                                        cancelSelection : true,
                                                        dateFormat : 'ddmmyyyy',
                                                        widgetOptions : {filter_saveFilters : true}
                                                        });
                        $(function(){
                          $('table').bind('filterEnd', function(){
                            update_totals();
                          });
                        });



                         function update_totals(){
                             $('[id^=total_item]').calc(
                                'qty * price',
                                {
                                    qty: $('span[id^=qta_arr-]'),
                                    price: $('span[id^=prz_dett_arr-]')
                                },
                                function (s){
                                    return s.toFixed(4);
                                }
                            );

                            $('#grand_total').html($('[id^=total_item]:visible').sum());
                        }

                         $('.edit').editable('".$RG_addr["rettifica_singoli_valori"]."', {
                             id   : 'elementid',
                             name : 'newvalue',
                             style: 'inherit',
                             submitdata :function(value, settings) {
                                   var id_dettaglio = $(this).data('id_dettaglio');
                                   var act = $(this).data('act');
                                   return {id_dettaglio: id_dettaglio, act:act};
                               },
                             height : 20,
                             width  : 80,
                             submit : 'OK',
                             callback : function(value, settings) {
                                 console.log(this);
                                 console.log(value);
                                 console.log(settings);
                                 update_totals();
                             }
                         });




                         update_totals();
                     });
                     </script>";


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{
    if($msg<>""){$r->messaggio = $msg;}
}
//Contenuto

$sql = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine';";
$result = $db->sql_query($sql);

$h = "  <form action=\"\" method=\"POST\">
        <table id=\"output_1\">
        <thead>
        <tr>
            <th>Data</th>
            <th>Utente</th>
            <th>Codice<br>Descrizione</th>
            <th>Quantità<br><span class=\"small_link\">Ordinata - Arrivata</span></th>
            <th>Prezzo unitario<br><span class=\"small_link\">Vecchio - Nuovo</span></th>
            <th>Tot Riga</th>
        </tr>
        </thead>
        <tbody>";

        $query_users = "SELECT

                maaking_users.fullname,
                maaking_users.email,
                maaking_users.user_site_option,
                retegas_referenze.id_gas_referenze,
                retegas_gas.descrizione_gas,
                maaking_users.userid
                FROM
                retegas_ordini
                Inner Join retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze
                Inner Join maaking_users ON retegas_referenze.id_gas_referenze = maaking_users.id_gas
                Inner Join retegas_gas ON retegas_referenze.id_gas_referenze = retegas_gas.id_gas
                WHERE
                retegas_ordini.id_ordini =  '$id_ordine'
                AND
                maaking_users.isactive = 1;";
        $res_users = $db->sql_query($query_users);

        while ($row = $db->sql_fetchrow($res_users)){

            if(read_option_text($row["userid"],"_USER_PERMETTI_MODIFICA")=="SI"){
                if(isset($id_utente_select)){
                    if($id_utente_select==$row["userid"]){$selected = " SELECTED ";}else{$selected="";}
                }
                $user_select .= '<option value="'.$row["userid"].'" '.$selected.'>'.$row["fullname"].' - '.$row["descrizione_gas"].'</option>\\n';

                }
        }


        $id_listino = listino_ordine_from_id_ordine($id_ordine);
        $query_articles = "SELECT * From retegas_articoli WHERE id_listini='$id_listino';";
        $res_articles = $db->sql_query($query_articles);

        while ($row = $db->sql_fetchrow($res_articles)){

            if(isset($id_articolo_select)){
                if($id_articolo_select==$row["id_articoli"]){$selected = " SELECTED ";}else{$selected="";}
            }
            $article_select .= '<option value="'.$row["id_articoli"].'" '.$selected.'>'.$row["codice"].' - '.$row["descrizione_articoli"].'</option>\\n';
        }



while ($row = $db->sql_fetchrow($result)){

    $id_dettaglio = $row["id_dettaglio_ordini"];
    $id_utente = $row["id_utenti"];
    $data = conv_datetime_from_db($row["timestamp_ord"]);
    $qta_ord = $row["qta_ord"];
    $qta_arr = $row["qta_arr"];
    $prz_dett = $row["prz_dett"];
    $prz_dett_arr = $row["prz_dett_arr"];

    if($qta_ord<>$qta_arr){

        $qta_diverso=" border-left: solid 5px #FC8600; padding-left:5px;";
    }else{
        $qta_diverso="";
    }

    if($prz_dett<>$prz_dett_arr){

        $prz_diverso=" border-left: solid 5px #FC8600; padding-left:5px;";
    }else{
        $prz_diverso="";
    }

    $codice = $row["art_codice"];
    $descrizione = $row["art_desc"];

    if($descrizione==""){
        $descrizione = articolo_sua_descrizione($row["id_articoli"]);
    }
    if($codice==""){
        $codice = articolo_suo_codice($row["id_articoli"]);
    }


    $h.="<tr>";
        $h.= "<td>$data</td>";
        $h.= "<td>".fullname_from_id($id_utente)."<br><span class=\"small_link\">".gas_nome(id_gas_user($id_utente))."</span></td>";
        $h.= "<td><span class=\"edit small_link\" id=\"codice-$id_dettaglio\" data-id_dettaglio=\"$id_dettaglio\" data-act=\"codice\">$codice</span><br><span class=\"edit small_link\" id=\"descrizione-$id_dettaglio\" data-id_dettaglio=\"$id_dettaglio\" data-act=\"descrizione\">$descrizione</span></td>";
        $h.= "<td style=\"$qta_diverso\">$qta_ord / <span class=\"edit\" id=\"qta_arr-$id_dettaglio\" data-id_dettaglio=\"$id_dettaglio\" data-act=\"qta_arr\" style=\"font-weight:bold;\">$qta_arr</span</td>";
        $h.= "<td style=\"$prz_diverso\">$prz_dett / <span class=\"edit\" id=\"prz_dett_arr-$id_dettaglio\" data-id_dettaglio=\"$id_dettaglio\" data-act=\"prz_dett_arr\" style=\"font-weight:bold;\">$prz_dett_arr</span></td>";
        $h.= "<td><div id=\"total_item_$id_dettaglio\"></div><a href=\"#\" data-id_dettaglio=\"$id_dettaglio\" data-id_utente=\"$id_utente\" class=\"awesome destra black option cancett\">Canc.</a></td>";
    $h.="</tr>";
}


$h .= " </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><div id=\"grand_total\"></div></th>
        </tr>
        <tr>
            <td><b>Aggiungi un articolo !</b></td>
            <td><select id=\"id_utente_select\" name=\"id_utente_select\" style=\"width:100%\">
            <option value=\"0\">Seleziona Utente</OPTION>
            ".$user_select."
            </select>
            </td>
            <td><select id=\"id_articolo_select\" name=\"id_articolo_select\" style=\"width:100%\">
            <option value=\"0\">Seleziona Articolo</OPTION>
            ".$article_select."
            </select></td>
            <td><input type=\"text\" name=\"quantita\" value=\"1.0000\"></td>
            <td>&nbsp;</td>
            <td>
                <input type=\"hidden\" name=\"act\" value=\"add_article\">
                <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
                <input type=\"submit\" value=\"aggiungi\" class=\"awesome small green\">
            </td>
        </tr>
        </tfoot>
        </table>
        </form>";



//Questo ?? il contenuto della pagina
$r->contenuto = "<div class=\"rg_widget rg_widget_helper\">
                <h3>Rettifica singoli valori</h3>
                <p><b>Istruzioni :</b> E' possibile correggere i valori sia delle quantità che quelli del costo di ogni articolo. Per modificare le cifre cliccare sopra a quelle che si intendono variare, e poi premere il pulsantino OK che appare al loro fianco. La tabella si può filtrare ed ordinare, cliccando sulle intestazioni delle colonne. I nuovi dati prendono da subito il posto di quelli vecchi, non occorre salvare.</p>
                <p>NB: si possono modificare solo le quantità e i prezzi riferiti alla merce ARRIVATA</p>
                <p>Anche la descrizione della singola riga è modificabile, ma questa variazione sarà visibile soltanto nella pagina LA MIA SPESA - DETTAGLIO ASSEGNAZIONI.</p>".$h."</div>";

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);