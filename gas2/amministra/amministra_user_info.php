<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("amministra_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}

if($do=="del$id_utente"){
   $id_utente = CAST_TO_INT(mimmo_decode($id_utente));
   $qry="DELETE FROM maaking_users WHERE userid='$id_utente' LIMIT 1;";
   
   echo $qry;
   $res = $db->sql_query($qry);
    
   go("amministra_ute_tutti",_USER_ID,"ELIMINATO <br> $qry"); 
}


$id_utente = mimmo_decode($id_utente);






//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Info Utente";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

$r->javascripts_header[]=java_head_jeditable();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_fullname').editable('".$RG_addr["ajax_admin_user_info"]."', { 
                             id   : 'elementid',
                             name : 'newvalue',
                             style: 'inherit',
                             submitdata : {type: 'fullname'},
                             height : 20,
                             width  : 200,
                             submit    : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_address_1').editable('".$RG_addr["ajax_admin_user_info"]."', { 
                             id   : 'elementid',
                             name : 'newvalue',
                             style: 'inherit',
                             submitdata : {type: 'address_1'},
                             height : 20,
                             width  : 200,
                             submit    : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_address_2').editable('".$RG_addr["ajax_admin_user_info"]."', { 
                             id   : 'elementid',
                             name : 'newvalue',
                             style: 'inherit',
                             submitdata : {type: 'address_2'},
                             height : 20,
                             width  : 200,
                             submit    : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_email').editable('".$RG_addr["ajax_admin_user_info"]."', { 
                             id   : 'elementid',
                             name : 'newvalue',
                             style: 'inherit',
                             submitdata : {type: 'email'},
                             height : 20,
                             width  : 200,
                             submit    : 'OK'
                         });
                     });
                     </script>";                     

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta


//Questo è¨ il contenuto della pagina
$r->contenuto = amministra_user_info($id_utente).
                amministra_opzioni_table("output_1",$id_utente);

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);
 
?>