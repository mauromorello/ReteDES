<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
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


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Rettifica prezzi articoli";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_jeditable();
$r->javascripts_header[]="<script type=\"text/javascript\" src=\"".$RG_addr["js_jcalc"]."\"></script>\n";
$r->javascripts[]="<script type=\"text/javascript\">
                    $(document).ready(function() {

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
}
//Contenuto

$sql = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' GROUP BY codice;";
$result = $db->sql_query($sql);

$h = "<table id=\"output_1\">
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
        $h.= "<td>$codice<br><span class=\"edit small_link\" id=\"descrizione-$id_dettaglio\" data-id_dettaglio=\"$id_dettaglio\" data-act=\"descrizione\">$descrizione</span></td>";
        $h.= "<td style=\"$qta_diverso\">$qta_ord / <span class=\"edit\" id=\"qta_arr-$id_dettaglio\" data-id_dettaglio=\"$id_dettaglio\" data-act=\"qta_arr\" style=\"font-weight:bold;\">$qta_arr</span</td>";
        $h.= "<td style=\"$prz_diverso\">$prz_dett / <span class=\"edit\" id=\"prz_dett_arr-$id_dettaglio\" data-id_dettaglio=\"$id_dettaglio\" data-act=\"prz_dett_arr\" style=\"font-weight:bold;\">$prz_dett_arr</span></td>";
        $h.= "<td><div id=\"total_item_$id_dettaglio\"></div></td>";
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
        </tfoot>
        </table>";



//Questo ?? il contenuto della pagina
$r->contenuto = "<div class=\"rg_widget rg_widget_helper\">
                <h3>Rettifica prezzi articoli</h3>
                <p><b>Istruzioni :</b> In questa tabella vengono presentati gli articoli presenti in questo ordine. E' possibile per ognuno variare il prezzo unitario, in modo che la differenza di prezzo venga riflessa a tutti coloro i quali lo hanno ordinato.</p>".$h."</div>";

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);