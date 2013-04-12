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
$r->title = "Stranezze utenti";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts[]=java_tablesorter("output_2");

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//--------------------------------------------CONTENUTO

//Cerco quelli che non hanno uno spazio
$sql = "SELECT * FROM maaking_users WHERE INSTR( maaking_users.fullname,' ')=0;";
$res = $db->sql_query($sql);
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Utenti senza nome e cognome</h3>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>ID</td>";
    $h .="<th>Nome</td>";
    $h .="<th>Gas</td>";
    $h .="<th>Mail</td>";
    $h .="<th>Tel</td>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    $h .="<tr>";
    $h .="<td><a href=\"".$RG_addr["pag_users_form"]."\">".$row["userid"]."</a></td>";
    $h .="<td>".$row["fullname"]."</td>";
    $h .="<td>".gas_nome(id_gas_user($row["userid"]))."</td>";
    $h .="<td>".$row["email"]."</td>";
    $h .="<td>".$row["tel"]."</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .="</table>";
$h .="</div><br>";


//Cerco quelli che hanno un numero di telefono sospetto
$sql = "SELECT * FROM maaking_users WHERE LENGTH( tel)<9;";
$res = $db->sql_query($sql);
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Utenti Con telefono corto</h3>";
$h .= "<table id=\"output_2\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>ID</td>";
    $h .="<th>Nome</td>";
    $h .="<th>Gas</td>";
    $h .="<th>Mail</td>";
    $h .="<th>Tel</td>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    $h .="<tr>";
    $h .="<td><a href=\"".$RG_addr["pag_users_form"]."\">".$row["userid"]."</a></td>";
    $h .="<td>".$row["fullname"]."</td>";
    $h .="<td>".gas_nome(id_gas_user($row["userid"]))."</td>";
    $h .="<td>".$row["email"]."</td>";
    $h .="<td>".$row["tel"]."</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .="</table>";
$h .="</div><br>";


//-----------------------------------------------------


//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>