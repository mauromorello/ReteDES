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

if($do=="do_rett"){

    $value_qarr=floatval(trim(str_replace(array(",","€"),array(".",""),$value_qarr)));
    $value_qord=floatval(trim(str_replace(array(",","€"),array(".",""),$value_qord)));


    $diff = CAST_TO_FLOAT($value_qarr-$value_qord);

    $sql = "SELECT * from retegas_dettaglio_ordini WHERE id_utenti='$id_ut' AND id_ordine='$id_ordine' AND art_codice='".dettaglio::rettifica."' LIMIT 1";
    $res = $db->sql_query($sql);
    $row = mysql_fetch_array($res);
    $dett_id=$row[0];

    $sql = "DELETE from retegas_distribuzione_spesa WHERE id_riga_dettaglio_ordine ='$dett_id';";
    $db->sql_query($sql);

    $sql = "DELETE from retegas_dettaglio_ordini WHERE id_utenti='$id_ut' AND id_ordine='$id_ordine' AND art_codice='".dettaglio::rettifica."' LIMIT 1";
    $db->sql_query($sql);

    $old_qarr = valore_arrivato_netto_ordine_user($id_ordine,$id_ut);
    $diff = round(CAST_TO_FLOAT($value_qarr-$old_qarr),4);

     if($diff<>0){
        $sql = "INSERT INTO  `my_retegas`.`retegas_dettaglio_ordini` (
                    `id_dettaglio_ordini` ,
                    `id_utenti` ,
                    `id_articoli` ,
                    `id_stati` ,
                    `data_inserimento` ,
                    `data_convalida` ,
                    `qta_ord` ,
                    `id_amico` ,
                    `id_ordine` ,
                    `qta_conf` ,
                    `qta_arr` ,
                    `timestamp_ord` ,
                    `prz_dett` ,
                    `prz_dett_arr` ,
                    `art_codice` ,
                    `art_desc` ,
                    `art_um`
                    )
                    VALUES (
                        NULL ,
                        '$id_ut',
                        '0',
                        '0',
                        NOW(),
                        '0000-00-00 00:00:00',
                        '1',
                        '0',
                        '$id_ordine',
                        '0.00',
                        '1',
                        CURRENT_TIMESTAMP ,
                        '$diff',
                        '$diff',
                        '".dettaglio::rettifica."',
                        'Disposta da "._USER_FULLNAME."' ,
                        NULL
                    );";
        $db->sql_query($sql);
        $msg = "User : ".fullname_from_id($id_ut)."<br>Nuovo totale : $value_qarr";


        //Trovo l'ultimo ID
        $res = $db->sql_query("SELECT LAST_INSERT_ID();");
        $row = mysql_fetch_array($res);
        $last_id=$row[0];

        //INSERISCO DISTRIBUZIONE
        $sql = "INSERT INTO  `my_retegas`.`retegas_distribuzione_spesa` (
                `id_distribuzione` ,
                `id_riga_dettaglio_ordine` ,
                `id_amico` ,
                `qta_ord` ,
                `qta_arr` ,
                `data_ins` ,
                `id_articoli` ,
                `id_user` ,
                `id_ordine` ,
                `id_gas`
                )
                VALUES (
                NULL ,
                '$last_id',
                '0',
                '1',
                '1',
                CURRENT_TIMESTAMP ,
                '0',
                '$id_ut',
                '$id_ordine',
                '".id_gas_user($id_ut)."'
                );";
        $db->sql_query($sql);

    }
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
$r->javascripts[]="<script type=\"text/javascript\">
                    $(document).ready(function() {

                        $('#output_1').tablesorter({widgets: ['zebra','filter','saveSort'],
                                                        cancelSelection : true,
                                                        dateFormat : 'ddmmyyyy',
                                                        widgetOptions : {filter_saveFilters : true}
                                                        });

                     });
                     </script>";


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{
 if(!empty($msg)){
     $r->messaggio = $msg;
 }
}
//Contenuto

$h .= " <div class=\"rg_widget rg_widget_helper\">
                <h3>Lista partecipanti</h3>

                <table id=\"output_1\">
                    <thead>
                        <tr>
                            <th>Utente</th>
                            <th>Gas</th>
                            <th>Conteggio ordini</th>
                            <th>Somma articoli</th>
                            <th>Importo totale netto su quantità ordinata</th>
                            <th>Importo rettificato netto su quantità arrivata</th>
                        </tr>
                    <thead>
                    <tbody>";


       $col_5 = "style=\"text-align:right;\"";

       $result = $db->sql_query("SELECT
                                    Sum(retegas_dettaglio_ordini.qta_ord * retegas_dettaglio_ordini.prz_dett) as importo_totale_qord,
                                    Sum(retegas_dettaglio_ordini.qta_arr * retegas_dettaglio_ordini.prz_dett_arr) as importo_totale_qarr,
                                    Sum(retegas_dettaglio_ordini.qta_ord) as somma_articoli,
                                    Count(retegas_dettaglio_ordini.id_articoli) as conto_articoli,
                                    maaking_users.fullname,
                                    maaking_users.userid,
                                    retegas_gas.id_gas,
                                    retegas_gas.descrizione_gas
                                    FROM
                                    retegas_dettaglio_ordini
                                    Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                                    Left Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                                    Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                                    WHERE
                                    retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
                                    GROUP BY
                                    retegas_dettaglio_ordini.id_utenti
                                    ORDER BY Sum(retegas_dettaglio_ordini.qta_arr * retegas_dettaglio_ordini.prz_dett_arr) DESC");


       $riga=0;
         while ($row = $db->sql_fetchrow($result)){



              $id_ut = $row["userid"];
              $nome_ut = $row['fullname'];
              $gas_app = $row['descrizione_gas'];
              $id_gas_app = $row['id_gas'];
              $conto_articoli = $row['conto_articoli'];
              $somma_articoli = $row['somma_articoli'];
              $importo_totale_ord = round($row["importo_totale_qord"],4);
              $importo_totale_arr = round($row["importo_totale_qarr"],4);

              $totalone_ord = $totalone_ord+ $importo_totale_ord;
              $totalone_arr = $totalone_arr+ $importo_totale_arr;
              $totalone_articoli = $totalone_articoli + $somma_articoli;

              //$importo_totale_ord = number_format($importo_totale_ord,2,",","");
              //$importo_totale_arr = number_format($importo_totale_arr,3,",","");



              $somma_articoli = (float)$somma_articoli;

              $importo_rettificato = '


                                      ';

              $h.= "<tr>";
                $h.= "<td $col_1>$nome_ut</td>";
                $h.= "<td $col_2>$gas_app</td>";
                $h.= "<td $col_5>$conto_articoli</td>";
                $h.= "<td $col_5>$somma_articoli</td>";
                $h.= "<td $col_5>$importo_totale_ord</td>";
                $h.= "<td $col_5>   <form action=\"\" method=\"POST\" class=\"retegas_form\">
                                    <input name=\"id_ordine\" type=\"hidden\" value=\"$id_ordine\">
                                    <input name=\"do\" type=\"hidden\" value=\"do_rett\" >
                                    <input name=\"id_ut\" type=\"hidden\" value=\"$id_ut\" >
                                    <input type=\"text\" name=\"value_qarr\" style=\"text-align:right\" size=5 value=\"$importo_totale_arr\">
                                    <input type=\"hidden\" name=\"value_qord\" value=\"$importo_totale_ord\">
                                    <input class=\"awesome green option\" style=\"\" type=\"submit\" value=\"OK\">
                                    </form>
                                    </td>";
              $h .="</tr>";

         }//end while


         $totalone_articoli = number_format($totalone_articoli,2,",","");
         $totalone_ord = number_format($totalone_ord,2,",","");
         $totalone_arr = number_format($totalone_arr,3,",","");

         $h.= "</tbody>
               <tfoot>
                <tr>
                    <th>&nbsp;</th>
                    <th>Somme:</th>
                    <th $col_5>&nbsp;</th>
                    <th $col_5>$totalone_articoli</th>
                    <th $col_5>$totalone_ord</th>
                    <th $col_5>$totalone_arr</th>
                </tr>
               </tfoot>
               </table>


               <center>

               </center>
               ";



//Questo ?? il contenuto della pagina
$r->contenuto = "<div class=\"rg_widget rg_widget_helper\">
                <h3>Rettifica totale utente aggiungendo una riga di rettifica</h3>
                <p><b>Istruzioni :</b> Con questo tipo di rettifica, si ottiene il totale voluto per ogni utente con l'aggiunta di una riga di \"rettifica\" nel dettaglio ordini. Quando si salva con il pulsante verde un nuovo valore, vengono cancellati quelli precedentemente creati; E' possibile inserire valori superiori o inferiori al totale effettivo, e verrà creata una riga positiva o negativa all'occorrenza.<br>
                La riga creata sarà visibile agli utenti nelle stampe usuali</p>".$h."</div>";

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);