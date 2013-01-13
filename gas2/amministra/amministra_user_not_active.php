<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     go("sommario",_USER_ID,"Non hai i permessi necessari");
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Utenti NON in stato ATTIVO";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




$query = "SELECT * FROM maaking_users WHERE isactive<>1;";
$res = $db->sql_query($query);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Utenti con stato NON attivo</h3>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>ID</th>";
    $h .="<th>Nome</th>";
    $h .="<th>Gas</th>";
    $h .="<th>Stato</th>";
    $h .="<th>Note</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    $motivo = "";
    switch ($row["isactive"]){
       case 0:
        $stato =  "In attesa attivazione";
       break;
       case 1:
        $stato =  "Attivo";
       break;
       case 2:
        $stato =  "Sospeso";
        $motivo = read_option_text($row["userid"],"_NOTE_SUSPENDED");
       break;
       case 3:
        $stato =  "Eliminato";
       break; 
        
        
    }
    
    $h .="<tr>";
    $h .="<td><a href=\"".$RG_addr["amministra_user_info"]."?id_utente=".mimmo_encode($row["userid"])."\">".($row["userid"])."</a></td>";
    $h .="<td><a href=\"".$RG_addr["user_form_public"]."?id_utente=".mimmo_encode($row["userid"])."\">".$row["fullname"]."</a></td>";
    $h .="<td>".gas_nome(id_gas_user($row["userid"]))."</td>";
    $h .="<td>$stato</td>";
    $h .="<td>$motivo</td>";
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