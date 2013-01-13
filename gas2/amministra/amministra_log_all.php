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



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Logs";


//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

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
//Contenuto

$sql = "SELECT * FROM retegas_messaggi ORDER BY id_messaggio DESC LIMIT 1000;";
$res = $db->sql_query($sql);


$h  = "<div class=\"rg_widget rg_widget_helper\" >";
$h .= "<h3>Ultimi Log</h3>";
$h .= "<table id=\"output_1\" >";
$h .= "<thead>";
    $h .= "<tr>";
        $h .= "<th data-sorter=\"shortDate\" data-filter=\"false\">TIME</th>";
        $h .= "<th>ID</th>";
        $h .= "<th>USER</th>"; 
        $h .= "<th>ORDINE</th>";
        $h .= "<th>T1</th>";
        $h .= "<th>T2</th>";
        $h .= "<th>VAL</th>";
        $h .= "<th class=\"filter-false\">MSG</th>";
        $h .= "<th class=\"filter-false\">NOTE</th>";
    $h .= "</tr>"; 
$h .= "</thead>";
$h .= "<tbody>";

while ($row = mysql_fetch_array($res)){

    $h .= "<tr>";
        $h .= "<td>".conv_date_from_db($row["timbro"])."</td>";
        $h .= "<td ><a href=\"".$RG_addr["amministra_log_single"]."?id_messaggio=".$row["id_messaggio"]."\">".$row["id_messaggio"]."</a></td>";
        $h .= "<td ><a href=\"".$RG_addr["amministra_user_info"]."?id_utente=".mimmo_encode($row["id_user"])."\">".$row["id_user"]."</a> - ".fullname_from_id($row["id_user"])." - ".gas_nome(id_gas_user($row["id_user"]))."</td>";
        $h .= "<td >".$row["id_ordine"]."</td>";
        $h .= "<td >".$row["tipo"]."</td>";
        $h .= "<td >".$row["tipo2"]."</td>";
        $h .= "<td >".$row["valore"]."</td>";
        $h .= "<td >".myTruncate($row["messaggio"],20," ")."</td>";
        $h .= "<td >".myTruncate($row["query"],20," ")."</td>";
    $h .= "</tr>";    
}    

$h .= "</tbody>";
$h .= "</table>";
$h .= "</div>";

//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>