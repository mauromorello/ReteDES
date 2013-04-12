<?php

include_once ("../rend.php");
include_once ("../retegas.class.php");
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}
$r = new rg_simplest();
$r->voce_mv_attiva = menu_lat::user;
$r->title = "User attivi di recente";
$r->menu_orizzontale = amministra_menu_completo();
$r->javascripts[]=java_tablesorter("output_1");
$r->messaggio = $msg;

$query = "SELECT * FROM maaking_users ORDER BY last_activity DESC LIMIT 20;";
$res = $db->sql_query($query);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Ultima attività sul sito</h3>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>ID</td>";
    $h .="<th>Nome</td>";
    $h .="<th>Gas</td>";
    $h .="<th>Last Login</td>";
    $h .="<th>Last Activity</td>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    
    $h .="<tr>";
    $h .="<td><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode($row["userid"])."\">".$row["userid"]."</a></td>";
    $h .="<td>".($row["fullname"])."</td>";
    $h .="<td>".gas_nome($row["id_gas"])."</td>";
    $h .="<td>".conv_datetime_from_db($row["lastlogin"])."</td>";
    $h .="<td>".conv_datetime_from_db($row["last_activity"])."</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .="</table>";
$h .="</div><br>";

$r->contenuto = $h;
echo $r->create_retegas();  
unset($r)   
?>