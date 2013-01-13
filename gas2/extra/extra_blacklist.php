<?php


  
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){
     pussa_via();
}

if($do=="ri"){
    $id_ordine = CAST_TO_INT($id_ordine);
    $sql = "DELETE FROM retegas_options WHERE id_ordine='$id_ordine' AND id_gas='"._USER_ID_GAS."' LIMIT 1";
    $db->sql_query($sql);
    log_me($id_ordine,_USER_ID,"BLL","","Removed from BL");
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Ordini Dashboard";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = extra_menu_all();


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto

$h.="<div class=\"rg_widget rg_widget_helper\">";
$h.= "<h3>Ordini nascosti (Blacklist)</h3>";

$query = "SELECT * FROM retegas_options WHERE id_gas='"._USER_ID_GAS."' AND chiave='BLACKLIST' ORDER BY timbro DESC";
$res = $db->sql_query($query);

while ($row = $db->sql_fetchrow($res)){
$h.="<div class=\"ui-corner-all\" style=\"padding:.5em; border:solid 1px #ccc; margin-bottom:1.5em; background-color:rgba(230,230,230,.8);\">";
$h.="";    
$h.="<b><a href=\"".$RG_addr["ordini_form"]."?id_ordine=".$row["id_ordine"]."\">".$row["id_ordine"]."</a>, ".descrizione_ordine_from_id_ordine($row["id_ordine"])."</b>, di ".fullname_referente_ordine_globale($row["id_ordine"])." del ".gas_nome(id_gas_user(id_referente_ordine_globale($row["id_ordine"])))."<br>";
$h.="<p>Nascosto il ".conv_datetime_from_db($row["timbro"])." da ".fullname_from_id($row["id_user"])."</p>";
$h.="<a class=\"awesome green option destra\" href=\"?do=ri&id_ordine=".$row["id_ordine"]."\">RIESUMALO</a>";
$h.="</div>";
}

$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);