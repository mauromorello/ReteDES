<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Temi usati";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

$query = "SELECT * FROM retegas_options WHERE chiave='_USER_OPT_THEME';";
$res = $db->sql_query($query);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Utenti Con Tema attivo</h3>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>ID</td>";
    $h .="<th>Nome</td>";
    $h .="<th>Gas</td>";
    $h .="<th>Tema</td>";
    $h .="<th>Aggiornato il</td>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    $h .="<tr>";
    $h .="<td><a href=\"".$RG_addr["pag_users_form"]."\">".$row["id_user"]."</a></td>";
    $h .="<td>".fullname_from_id($row["id_user"])."</td>";
    $h .="<td>".gas_nome(id_gas_user($row["id_user"]))."</td>";
    $h .="<td>".$row["valore_text"]."</td>";
    $h .="<td>".conv_datetime_from_db($row["timbro"])."</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .="</table>";
$h .="</div><br>";

//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>