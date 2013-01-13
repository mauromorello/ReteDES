<?php


   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}

if($do<>"filter"){
   // $date = new DateTime(date("Y-m-d"));
   //$data_da = $date;
   // $date->modify('+1 day');
   // $data_a = $date;
}
    $filter = " data_inserimento >= '".conv_date_to_db($data_da)."' and data_inserimento < '".conv_date_to_db($data_a)."'";   
    $sql = "SELECT * FROM retegas_dettaglio_ordini WHERE $filter ORDER BY id_dettaglio_ordini DESC";
    $res = $db->sql_query($sql);
    
//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Movimenti intervallo";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_jquery_datepicker();
$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts[]=java_datepicker("pick_1");
$r->javascripts[]=java_datepicker("pick_2");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Tutti i movimenti</h3>";
$h .= "<form>
        <input type=\"text\" id=\"pick_1\" name=\"data_da\" value=\"$data_da\">
        <input type=\"text\" id=\"pick_2\" name=\"data_a\"  value=\"$data_a\">
        <input type=\"hidden\" name=\"do\" value=\"filter\">
        <input type=\"Submit\" value=\"Submit\">
       </form>";
$h .= "<div class=\"\">";
$h .= "<p>FILTRO : $sql</p>";
$h .= "</div>";        
$h .= "<table id=\"output_1\">";
    $h .= "<thead>";
        $h .= "<tr>";
            $h .= "<th>ID RIGA</th>";
            $h .= "<th data-sorter=\"dd/mm/yyyy\">TIMESTAMP</th>";
            $h .= "<th>USER</th>";
            $h .= "<th>NAME</th>";
            $h .= "<th>GAS</th>";
            $h .= "<th>ORDER</th>";
            $h .= "<th>ART</th>";
            $h .= "<th>DESC</th>";
            $h .= "<th>QORD</th>";
            $h .= "<th>QARR</th>";
            $h .= "<th>PRICE</th>";
            $h .= "<th>ROW_TOT</th>";
        $h .= "</tr>";
    $h .= "</thead>";
$h .= "<tbody>";

while ($row = $db->sql_fetchrow($res)){
    
    $prezzo = articolo_suo_prezzo($row["id_articoli"]);
    $tot_riga = $prezzo * $row["qta_arr"];
    
    
    $h.="<tr>";
        $h.="<td>".$row["id_dettaglio_ordini"]."</td>";
        $h.="<td>".conv_date_from_db($row["timestamp_ord"])."</td>";
        $h.="<td>".$row["id_utenti"]."</td>";
        $h.="<td>".fullname_from_id($row["id_utenti"])."</td>";
        $h.="<td>".gas_nome(id_gas_user($row["id_utenti"]))."</td>";
        $h.="<td>".$row["id_ordini"]."</td>";
        $h.="<td>".$row["id_articoli"]."</td>";
        $h.="<td>".articolo_sua_descrizione($row["id_articoli"])."</td>";
        $h.="<td>".$row["qta_ord"]."</td>";
        $h.="<td>".$row["qta_arr"]."</td>";
        $h.="<td class=\"destra\">"._nf($prezzo)."</td>";
        $h.="<td class=\"destra\">"._nf($tot_riga)."</td>";
    $h.="</tr>";
    
    $tot_table = $tot_table + $tot_riga;
}

$h .= "</tbody>";
$h .= "<tfoot>";
$h .= "<tr>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th>&nbsp;</th>";
            $h .= "<th class=\"destra\">"._nf($tot_table)."</th>";
        $h .= "</tr>";
$h .= "</tfoot>";
$h .= "</table>";
$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>