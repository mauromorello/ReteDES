<?php


//Togliere quelli che non interessano
$_FUNCTION_LOADER=array("widgets",
                        "gphpcharts",
                        "swift",
                        "posta",
                        "amici",
                        "gas",
                        "listini",
                        "ditte",
                        "tipologie",
                        "articoli",
                        "graphics",
                        "ordini",
                        "ordini_valori",
                        "bacheca",
                        "geocoding",
                        "admin",
                        "dareavere",
                        "cassa",
                        "theming");
   
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

if(!isset($id_messaggio)){
    pussa_via();
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Singolo Log Msg";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto

$sql = "SELECT * FROM retegas_messaggi WHERE id_messaggio > '$id_messaggio' ORDER BY id_messaggio LIMIT 1;";
$res = $db->sql_query($sql);
$row = $db->sql_fetchrow($res);
$next = "<a class=\"awesome transparent big\" onclick=\"
                                                        $.ajax({
                                                          url: '".$RG_addr["ajax_schedina_log"]."?id_messaggio=".$row[0]."',
                                                          success: function(data) {
                                                            $('#log_container').html(data);
                                                          }
                                                        });
                                                        \">SUCCESSIVO</a>";

$sql = "SELECT * FROM retegas_messaggi WHERE id_messaggio < '$id_messaggio' ORDER BY id_messaggio DESC LIMIT 1;";
$res = $db->sql_query($sql);
$row = $db->sql_fetchrow($res);
$previous = "<a class=\"awesome transparent big\" onclick=\"
                                                        $.ajax({
                                                          url: '".$RG_addr["ajax_schedina_log"]."?id_messaggio=".$row[0]."',
                                                          success: function(data) {
                                                            $('#log_container').html(data);
                                                          }
                                                        });
                                                        \">PRECEDENTE</a>";

$sql = "SELECT * FROM retegas_messaggi WHERE id_messaggio='$id_messaggio' LIMIT 1;";
$res = $db->sql_query($sql);
$row = $db->sql_fetchrow($res);

$h .="<div id=\"log_container\" class=\"rg_widget rg_widget_helper\">";
$h .="<center>$previous <strong>$id_messaggio</strong> $next</center>";
$h .="<p><strong>Id_messaggio: </strong>".$row["id_messaggio"]."</p>";
$h .="<p><strong>id_user: </strong>".$row["id_user"]." - ".fullname_from_id($row[1])." ".gas_nome(id_gas_user($row[1]))."</p>";
$h .="<p><strong>id_ordine: </strong>".$row["id_ordine"]." - ".fullname_from_id($row[2])." ".descrizione_ordine_from_id_ordine($row[2])."</p>";
$h .="<p><strong>messaggio: </strong>".$row["messaggio"]."</p>";
$h .="<p><strong>Timestamp: </strong>".$row["timbro"]."</p>";
$h .="<p><strong>COD 1: </strong>".$row["tipo"]."</p>";
$h .="<p><strong>COD 2: </strong>".$row["tipo2"]."</p>";
$h .="<p><strong>Valore: </strong>".$row["valore"]."</p>";
$h .="<p><strong>Verbose: </strong>".$row["query"]."</p>";
$h .="</div>";
//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>