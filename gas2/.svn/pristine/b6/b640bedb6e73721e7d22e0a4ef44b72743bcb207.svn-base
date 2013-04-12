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
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Temi usati";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

// rgw_1 = ordini_chiusi
// rgw_2 = ordini_aperti
// rgw_3 = ordini futuri
// rgw_4 = bachechina
// rgw_5 = Grafico utilizzo sito (torta)
// rgw_6 = Utenti Online 
// rgw_7 = retegas Comunica
// rgw_8 = chat
// rgw_9 = Alerts utenti
// rgw_10 = tutti gli ordini
// rgw_11 = Ordini io coinvolto
// rgw_12 = Utenti mio gas
// rgw_13 = Movimenti Cassa Utente

$widgets = array(
"1"=>"Ordini chiusi",
"2"=>"ordini_aperti",
"3"=>"ordini futuri",
"4"=>"bachechina",
"5"=>"Grafico utilizzo sito (torta)",
"6"=>"Utenti Online ",
"7"=>"retegas Comunica",
"8"=>"chat",
"9"=>"Alerts utenti",
"10"=>"tutti gli ordini",
"11"=>"Ordini io coinvolto",
"12"=>"Utenti mio gas",
"13"=>"Movimenti Cassa Utente",
"14"=>"Ordine singolo 1"
);

$query = "SELECT * FROM retegas_options WHERE chiave='WGO';";
$res = $db->sql_query($query);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Utenti Con Widgets</h3>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>ID</td>";
    $h .="<th>Nome</td>";
    $h .="<th>Gas</td>";
    $h .="<th>Widgets</td>";
    $h .="<th>Aggiornato il</td>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    
    $wi = unserialize(base64_decode($row["valore_text"]));
    $tw = count($wi);
    $c = "";
    for($i=0;$i<$tw;$i++){
       //if(($wi[$i]<> 7) AND ($wi[$i]<>9)){ 
       $c .= $widgets[$wi[$i]]."<br>";
       //}
    }
    
    $h .="<tr>";
    $h .="<td><a href=\"".$RG_addr["pag_users_form"]."?id=".mimmo_encode($row["id_user"])."\">".($row["id_user"])."</a></td>";
    $h .="<td>".fullname_from_id($row["id_user"])."</td>";
    $h .="<td>".gas_nome(id_gas_user($row["id_user"]))."</td>";
    $h .="<td>".$c."</td>";
    $h .="<td>".conv_datetime_from_db($row["timbro"])."</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .="</table>";
$h .="</div><br>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>