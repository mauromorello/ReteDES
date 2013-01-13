<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

!isset($limit) ? $limit=100 : $limit=CAST_TO_INT($limit,0,1000);

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Ordini chiusi";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$id_ordine = null;
$r->menu_orizzontale[] = ordini_menu_visualizza($user,$id_ordine);


//Assegno le due tabelle a tablesorter
$r->javascripts[]='<script type="text/javascript">                
                        $.tablesorter.addWidget({
    id: \'FilterSave\',
    format: function(table, init){
        var sl, time, c = table.config,
            wo = c.widgetOptions,
            ss = wo.FilterSave !== false; // make FilterSave active/inactive; default to true

        var count_filters = $(table).find(\'input.tablesorter-filter\').length;
        var filter = new Array();
        for (var i=0; i<count_filters;i++)
        {
            filter[i]=$(table).find(\'input.tablesorter-filter\').eq(i).val();
        }

        if (c.debug){
            time = new Date();
        }
        if ($(table).hasClass(\'hasSaveFilter\')){
            if (ss && table.hasInitialized && $.tablesorter.storage){
                $.tablesorter.storage( table, \'tablesorter-savefilter\', filter );
                 console.log("saved"+filter)
                if (c.debug){
                    $.tablesorter.benchmark(\'FilterSave widget: Saving last filter: \' + filter, time);
                }
            }
        } else {
            // set table sort on initial run of the widget
            $(table).addClass(\'hasSaveFilter\');
            filter = \'\';
            // get data
            if ($.tablesorter.storage){
                fl = $.tablesorter.storage( table, \'tablesorter-savefilter\' );
                filter = (fl && $.isArray(fl)) ? fl : \'\';
                if (c.debug){
                    $.tablesorter.benchmark(\'FilterSave: Last filter loaded: "\' + filter + \'"\', time);
                }
            }
            // init is true when widget init is run, this will run this widget before all other widgets have initialized
            // this method allows using this widget in the original tablesorter plugin; but then it will run all widgets twice.
            //if (filter && filter.length > 0)
            //{
            $(table).trigger(\'search\', [filter]);
            //}
        }
    },
    remove: function(table, c, wo){
        // clear storage
        $.tablesorter.storage( table, \'tablesorter-savefilter\', \'\' );
    }
});
                        
                        
                        $(document).ready(function() 
                            {
                                $("#output_1").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\',\'FilterSave\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',                                                               
                                                        }); 
                                } 
                            );
</script>';


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta


$query = "SELECT * FROM retegas_ordini WHERE data_chiusura < NOW() ORDER BY data_chiusura DESC;";
$res = $db->sql_query($query);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Ordini chiusi</h3>";
$h .= "<form method=\"POST\" action=\"\">Mostra al massimo <input type=\"text\" size=\"10\" name=\"limit\" value=\"$limit\">ordini. <input type=\"submit\" value=\"aggiorna\"></form>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>Stato</th>";
    $h .="<th>ID</th>";
    $h .="<th>Descrizione</th>";
    $h .="<th>Ditta</th>";
    $h .="<th>Proposto</th>";
    $h .="<th data-sorter=\"shortDate\" >Data Chiusura</th>";
    $h .="<th>Mia Spesa</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    if($ord > ($limit-1)){break;};
    if(gas_partecipa_ordine($row["id_ordini"],_USER_ID_GAS)>0){
        $ord++;
        
        $row["is_printable"] ? $pal="CONF. ".pallino("grigio",16): $pal="DA CONF. ".pallino("rosso",16);
        
        //$vto = valore_totale_lordo_ordine($row["id_ordini"]);
        $vmo = valore_totale_mio_ordine_lordo($row["id_ordini"],_USER_ID);
        
        $t_vto = $t_vto + $vto;
        $t_vmo = $t_vmo + $vmo;
        
        $h .="<tr>";
        $h .="<td>$pal</td>";
        $h .="<td><a href=\"".$RG_addr["ordini_form"]."?id_ordine=".$row["id_ordini"]."\">".$row["id_ordini"]."</a></td>";
        $h .="<td><a href=\"".$RG_addr["ordini_form"]."?id_ordine=".$row["id_ordini"]."\">".($row["descrizione_ordini"])."</a></td>";
        $h .="<td>".ditta_nome_from_listino($row["id_listini"])."</td>";
        $h .="<td>".gas_nome(id_gas_user(id_referente_ordine_globale($row["id_ordini"])))."</td>";
        $h .="<td>".conv_datetime_from_db($row["data_chiusura"])."</td>";
        //$h .="<td>".round($vto,2)."</td>";
        $h .="<td class=\"destra\">"._nf($vmo)."</td>";
        $h .="</tr>";
    }
}
$h .="</tbody>";
$h .= "<tfoot>";
    $h .="<tr>";
    $h .="<th>&nbsp</td>";
    $h .="<th>&nbsp</td>";
    $h .="<th>Totali</td>";
    $h .="<th>&nbsp</td>";
    $h .="<th>&nbsp</td>";
    $h .="<th>&nbsp</td>";
    //$h .="<th>".round($t_vto,2)."</td>";
    $h .="<th>".round($t_vmo,2)."</td>";
    $h .="</tr>";
$h .= "</tfoot>";
$h .="</table>";
$h .="</div><br>";

//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>