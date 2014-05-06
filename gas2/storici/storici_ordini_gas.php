<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}

//SE non è settato id gas lo metto come il nativo
if (!isset($id_gas)){$id_gas=_USER_ID_GAS;};

if(!controllodata($dat_1)){
    $dat_1 = "01/01/2012";
}
if(!controllodata($dat_2)){
    $dat_2 = date("d/m/Y");
}

if(gas_mktime($dat_1)>gas_mktime($dat_2)){
    $dat_1 = "01/01/2012";
    $dat_2 = date("d/m/Y");
}


$id_ditta = CAST_TO_INT($id_ditta);
if($id_ditta>0){
    $filtro_ditta=" AND retegas_listini.id_ditte='$id_ditta' ";
    $title_ditta=", solo la ditta \"$id_ditta\" ";
}else{
    $filtro_ditta="";
    $title_ditta=", tutte le ditte ";
}


$data_1 = conv_date_to_db($dat_1);
$data_2 = conv_date_to_db($dat_2);


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Storici ordini GAS";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
//                                       <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=html">HTML</a></li>
//                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">PDF</a></li>
    $r->menu_orizzontale = storici_menu_completo();
    $r->menu_orizzontale[] =  '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="?output=html">HTML</a></li>
                                        <li><a class="awesome medium silver"  href="?output=pdf&cod='.rand(0,999999999).'&dat_1='.$dat_1.'&dat_2='.$dat_2.'&id_ditta='.$id_ditta.'">PDF</a></li>
                                        <li><a class="awesome medium silver"  href="#" onclick="getCSVData();$(\'#form_csv\').submit();">CSV</a></li>
                                    </ul>
                                </li>';

//Assegno le due tabelle a tablesorter
$ref_table = "out_1";

$r->javascripts_header[]="
<script type=\"text/javascript\">
jQuery.fn.table2CSV = function(options) {
    var options = jQuery.extend({
        separator: '"._USER_CSV_SEPARATOR."',
        header: [],
        delivery: 'popup' // popup, value
    },
    options);

    var csvData = [];
    var headerArr = [];
    var el = this;

    //header
    var numCols = options.header.length;
    var tmpRow = []; // construct header avalible array

    if (numCols > 0) {
        for (var i = 0; i < numCols; i++) {
            tmpRow[tmpRow.length] = formatData(options.header[i]);
        }
    } else {
        $(el).filter(':visible').find('th').each(function() {
            if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
        });
    }

    row2CSV(tmpRow);

    // actual data
    $(el).find('tr').each(function() {
        var tmpRow = [];
        $(this).filter(':visible').find('td').each(function() {
            if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
        });
        row2CSV(tmpRow);
    });
    if (options.delivery == 'popup') {
        var mydata = csvData.join('\\n');
        return popup(mydata);
    } else {
        var mydata = csvData.join('\\r\\n');
        return mydata;
    }

    function row2CSV(tmpRow) {
        var tmp = tmpRow.join('') // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(options.separator);
            csvData[csvData.length] = mystr;
        }
    }
    function formatData(input) {
        // replace \" with “
        var regexp = new RegExp(/[\"]/g);
        var output = input.replace(regexp, \"“\");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, \"\");
        if (output == \"\") return '';
        return '"._USER_CSV_DELIMITER."' + output + '"._USER_CSV_DELIMITER."';
    }
    function popup(data) {
        var generator = window.open('', 'csv', 'height=400,width=600');
        generator.document.write('<html><head><title>CSV</title>');
        generator.document.write('</head><body >');
        generator.document.write('<textArea cols=70 rows=15 wrap=\"off\" >');
        generator.document.write(data);
        generator.document.write('</textArea>');
        generator.document.write('</body></html>');
        generator.document.close();
        return true;
    }
};
</script>\n
";
$r->javascripts_header[] = "<script type=\"text/javascript\" src=\"".$RG_addr["js_datepicker_loc"]."\"></script>\n";
$r->javascripts[]= java_tablesorter($ref_table);
$r->javascripts[]= java_datepicker("dat_1");
$r->javascripts[]= java_datepicker("dat_2");
$r->javascripts[]="<script type=\"text/javascript\">
                        function getCSVData(){
                         var csv_value=$('#$ref_table').table2CSV({delivery:'value'});
                         $(\"#csv_text\").val(csv_value);
                         }
                        </script>";

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}

if($includi_gas_esterni=="SI"){$checked=" CHECKED ";
                               $title_inc = " inclusi gli ordini condivisi.";}
                               
//Contenuto
$h_h .= "     <div class=\"ui-state-error ui-corner-all\">
            <ul>
                <li>E' possibile filtare i dati in base alle chiusure degli ordini ed al codice della ditta.</li>
                <li>La pagina di aiuto è <a href=\"https://sites.google.com/site/retegasapwiki/come-fare-per/gestire-gli-storici/storico-ordini-gas\">QUI</a></li>
            </ul>
            </div>
            <br>";    
$h_h .= rg_toggable("Filtri applicabili","filtri","
                   
                    <form class=\"retegas_form\" method=\"POST\" action=\"\">
                    <label for=\"data_da\">Data iniziale (compresa)</label>
                    <input name=\"dat_1\" type=\"text\" id=\"dat_1\" size=\"10\" value=\"$dat_1\"></input>
                    <br>
                    
                    <label for=\"data_a\">Data finale (esclusa)</label>
                    <input name=\"dat_2\" type=\"text\" id=\"dat_2\" size=\"10\" value=\"$dat_2\"></input>
                    <br>
                    
                    <label for=\"id_ditta\">Cod. ditta</label>
                    <input name=\"id_ditta\" type=\"numeric\" id=\"id_ditta\" size=\"3\" value=\"$id_ditta\"></input>
                    <br>
                    
                    <label for=\"includi_gas_esterni\">Includi ordini condivisi da altri gas</label>
                    <input name=\"includi_gas_esterni\" type=\"checkbox\" id=\"includi_gas_esterni\" $checked value=\"SI\"></input>
                    <br>
                    
                    <input type=\"hidden\" name=\"do\" value=\"filter\">
                    <input type=\"hidden\" name=\"filter\" value=\"date\">
                    <input type=\"submit\" name=\"submit\" value=\"Filtra i dati\" align=\"left\" >
            </form>");
            
$h="        <form action=\"storici_ordini_gas.php\"  method =\"post\" id=\"form_csv\" class=\"hidden\"> 
                    <input type=\"hidden\" name=\"csv_text\" id=\"csv_text\">
                    <input type=\"hidden\" name=\"output\" value=\"csv\"> 
                    <input type=\"submit\" value=\"Esporta in CSV\" 
                           onclick=\"getCSVData();\">
                </form>
                
                <table id=\"$ref_table\">
                    <thead>     
                        <tr class=\"destra\">
                            <th class=\"sinistra\">Ordine</th>
                            <th class=\"sinistra\">Descrizione</th> 
                            <th class=\"sinistra\">Ditta</th>
                            <th class=\"sinistra\">Referente</th>
                            <th class=\"sinistra\" data-sorter=\"shortDate\" data-date-format=\"ddmmyyyy\">Chiuso il</th>
                            
                            <th class=\"{sorter: 'digit'}\">Netto</th>
                            <th>Trasporto</th>
                            <th>Gestione</th>
                            <th>Lordo Pubblico</th>
                            <th>Costo GAS</th>
                            <th>% GAS</th>
                            <th>Lordo Privato</th> 
                        </tr>
                    <thead>
                    <tbody>";
$col_5 = " class=\"destra\" "; 
$sql_int ="SELECT
        retegas_ordini.id_ordini,
        retegas_ordini.descrizione_ordini,
        retegas_ordini.data_chiusura,
        costo_trasporto,
        costo_gestione,
        maaking_users.fullname,
        retegas_listini.id_ditte,
        retegas_ditte.descrizione_ditte
        FROM
        retegas_ordini
        Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
        Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
        Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
        WHERE
        maaking_users.id_gas =  '$id_gas' AND
        retegas_ordini.data_chiusura BETWEEN  '$data_1' AND '$data_2'
        $filtro_ditta
        ORDER BY data_chiusura DESC;";

$sql_ext = "SELECT
retegas_referenze.id_utente_referenze,
retegas_ordini.descrizione_ordini,
retegas_ordini.data_chiusura,
maaking_users.fullname,
retegas_ordini.id_ordini,
retegas_listini.id_ditte,
retegas_ditte.descrizione_ditte,
maaking_users.id_gas
FROM
retegas_referenze
Inner Join retegas_ordini ON retegas_referenze.id_ordine_referenze = retegas_ordini.id_ordini
Inner Join maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid
Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
WHERE
retegas_referenze.id_gas_referenze =  '$id_gas' AND
retegas_referenze.id_utente_referenze <>  '0' AND
retegas_ordini.data_chiusura BETWEEN  '$data_1' AND '$data_2'
$filtro_ditta
ORDER BY data_chiusura DESC;";


if($includi_gas_esterni=="SI"){
   $res =  $db->sql_query($sql_ext); 
}else{
   $res =  $db->sql_query($sql_int); 
}
                    

while ($row = $db->sql_fetchrow($res)){
     
     $gestore = $row["fullname"];

     
    
     $id_ordine = $row["id_ordini"];
     
     $id_proponente_gen = id_referente_ordine_globale($id_ordine);
     $id_proponente_gas = id_referente_ordine_proprio_gas($id_ordine,$id_gas);
     
     if($id_proponente_gen<>$id_proponente_gas){
         
         $gestore .= " (Ordine di ".fullname_from_id($id_proponente_gen)." del ".gas_nome(id_gas_user($id_proponente_gen)).")";
     }
     
     
     $id_ditte = $row["id_ditte"];
     $descrizione_ditta = $id_ditte ." - ". $row["descrizione_ditte"];
     
     $descrizione = $row["descrizione_ordini"];
     $data_chiusura = conv_datetime_from_db($row["data_chiusura"]);

     $costo_trasporto = valore_costo_trasporto_ordine_gas($id_ordine,$id_gas);
     $costo_gestione =  valore_costo_gestione_ordine_gas($id_ordine,$id_gas);
                       
     $costo_mio_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
     $costo_maggiorazione = valore_reale_maggiorazione_percentuale_gas($id_ordine,$id_gas);
     
     $importo_totale = valore_totale_mio_gas($id_ordine,$id_gas);
     
     $totale_lordo = $costo_trasporto +
                        $costo_gestione +
                        $costo_mio_gas +
                        $costo_maggiorazione +
                        $importo_totale;    
     
     $totale_pubblico = $importo_totale +
                        $costo_gestione +
                        $costo_trasporto;
     
     
     $GT_totale_pubblico = $GT_totale_pubblico + $totale_pubblico;
     $GT_totale_privato = $GT_totale_privato + $totale_lordo;
     
     $importo_totale = _nf($importo_totale);
     $totale_lordo = _nf($totale_lordo);
     $totale_pubblico = _nf($totale_pubblico);
     $costo_trasporto = _nf($costo_trasporto);
     $costo_gestione = _nf($costo_gestione);
     $costo_maggiorazione = _nf($costo_maggiorazione);
     $costo_mio_gas = _nf($costo_mio_gas);
     
     $h.= "<tr>";
                $h.= "<td class=\"sinistra\">$id_ordine</td>";
                $h.= "<td class=\"sinistra\">$descrizione</td>";    
                $h.= "<td class=\"sinistra\">$descrizione_ditta</td>";
                $h.= "<td class=\"sinistra\">$gestore</td>";
                $h.= "<td class=\"sinistra\">$data_chiusura</td>"; 
                //$h.= "<td class=\"sinistra\">-----</td>";
                //$h.= "<td $col_5>$conto_articoli</td>";
                //$h.= "<td $col_5>$somma_articoli</td>";
                $h.= "<td $col_5>$importo_totale</td>";
                $h.= "<td $col_5>$costo_trasporto</td>";
                $h.= "<td $col_5>$costo_gestione</td>";
                $h.= "<td $col_5><b>$totale_pubblico</b></td>";
                $h.= "<td $col_5>$costo_mio_gas</td>";
                $h.= "<td $col_5>$costo_maggiorazione</td>";
                $h.= "<td $col_5><b>$totale_lordo</b></td>";
     $h .="</tr>";
}                    


$GT_totale_pubblico = _nf($GT_totale_pubblico);
$GT_totale_privato = _nf($GT_totale_privato);

$h.= "</tbody>
      <tfoot>
      <tr class=\"total\">
        <td colspan=8>&nbsp;</td>
        <td class=\"destra\">$GT_totale_pubblico</td>
        <td class=\"destra\">&nbsp;</td>
        <td class=\"destra\">&nbsp;</td>
        <td class=\"destra\">$GT_totale_privato</td>
      </tr>
      </tfoot>
      </table>";
                    
//Creo l'intestazione per il pdf e l'html
//devo assegnare l'url relativa dell'immagine del logo
//Formattazione PDF e HTML
//Uso lo stesso foglio di stile della pagina video
//a cui sovrappongo un po' di margine ai bordi
//I caratteri sono a punti
$s=load_pdf_styles("../css/");

$title_page="Riepilogo ordini ".gas_nome($id_gas).", dal ".conv_date_from_db($data_1)." al ".conv_date_from_db($data_2)."$title_ditta $title_inc";


if(_USER_OPT_NO_HEADER=="SI"){
    $i="";
    $o="<h3>$title_page</h3>";

}else{
    $i=load_pdf_header("../images/rg.jpg");
    $o="<h3>$title_page</h3>";;    
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->set_paper("A4","landscape");
    $dompdf->render();
    $dompdf->stream("dati_".$id_gas."-$cod.pdf",array("Attachment" => 0));
    die();
    
}elseif($output=="html"){
    echo $h;
}elseif($output=="csv"){
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"Riepilogo_ordini.csv\"");
    
    $data=stripcslashes($_REQUEST['csv_text']);
    print $data; 
}else{
    $r->contenuto = "<div class=\"rg_widget rg_widget_helper\">
                    <h3>$title_page</h3>"
                    .$h_h.$h
                    ."</div>";
                   
    echo $r->create_retegas();
}
//Distruggo l'oggetto r    
unset($r)    
?>