<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLO SE E' IL CAPO DEL GAS
if(db_val_q("id_gas",_USER_ID_GAS,"id_referente_gas","retegas_gas")<>_USER_ID){
     pussa_via();
}

if(!isset($id_utente)){
    pussa_via();
}

$id_utente = mimmo_decode($id_utente);

// CONTROLLO SE L'UTENTE E' UN SUO UTENTE
if(id_gas_user($id_utente)<>_USER_ID_GAS){
    pussa_via();
}



if($do=="save"){
    
    $act_perm = leggi_permessi_utente($id_utente);
    //set : REGISTER |= (1 << PIN_POSITION)
    //reset : REGISTER &= ~(1 << PIN_POSITION)
    //AJAX RESPONSE
    //Echo $id." ".$checked."<br>";
    switch ($id) {
    case "sp_bacheca_on":
        $new_perm = $act_perm |  perm::puo_eliminare_messaggi;
        break;
    case "sp_bacheca_off":
        $new_perm = $act_perm &  (~perm::puo_eliminare_messaggi);
        break;
    case "sp_cassa_on":
        $new_perm = $act_perm |  perm::puo_gestire_la_cassa;
        break;
    case "sp_cassa_off":
        $new_perm = $act_perm &  (~perm::puo_gestire_la_cassa);
        break;
    case "sp_permutenti_on":
        $new_perm = $act_perm |  perm::puo_mod_perm_user_gas;
        break;
    case "sp_permutenti_off":
        $new_perm = $act_perm &  (~perm::puo_mod_perm_user_gas);
        break;        
    case "sp_utenti_on":
        $new_perm = $act_perm |  perm::puo_gestire_utenti;
        break;
    case "sp_utenti_off":
        $new_perm = $act_perm &  (~perm::puo_gestire_utenti);
        break;
    case "sp_ordini_on":
        $new_perm = $act_perm |  perm::puo_vedere_tutti_ordini;
        break;
    case "sp_ordini_off":
        $new_perm = $act_perm &  (~perm::puo_vedere_tutti_ordini);
        break;
    case "sp_gas_on":
        $new_perm = $act_perm |  perm::puo_creare_gas;
        break;
    case "sp_gas_off":
        $new_perm = $act_perm &  (~perm::puo_creare_gas);
        break;                
    case "":
        
        break;
                
}
    log_me(0,_USER_ID,"PRM","CHG","$id Old :$act_perm New : $new_perm",0,"<br>
                                                                            USER ID : $id_utente<br>
                                                                          PERM : $id<br>
                                                                          OLD : $act_perm<br>
                                                                          NEW : $new_perm");
    $db->sql_query("UPDATE maaking_users SET user_permission = '$new_perm' WHERE userid='$id_utente' LIMIT 1");
    //AJAX RESPONSE
    echo utenti_scheda_permessi($id_utente);
    die();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Super poteri";
$r->menu_orizzontale[] = menu_visualizza_user(_USER_ID);
$r->menu_orizzontale[] = menu_gestisci_user(_USER_ID,$id_utente);
$r->menu_orizzontale[] = menu_gestisci_user_cassa(_USER_ID,$id_utente);

$r->javascripts_header[] ='
    <script type="text/javascript">
    
$(document).ready( function(){ 
    $(".cb-enable").click(function(){
        var parent = $(this).parents(\'.switch\');
        $(\'.cb-disable\',parent).removeClass(\'selected\');
        $(this).addClass(\'selected\');
        $(\'.checkbox\',parent).attr(\'checked\', true);
        var menuId = $(this).attr("id");
        $.ajax({
          url: "'.$RG_addr["utenti_superpoteri"].'",
          cache: false,
          type: "POST",
          data: {   id_utente :"'.mimmo_encode($id_utente).'",
                    do: "save", 
                    id : menuId},
          dataType: "html"
        }).done(function( html ) {
          $($(parent).parent()).removeClass(\'desel\');
          $($(parent).parent()).addClass(\'sel\');
          $(\'#result\').html(html);
        });
    });
    $(".cb-disable").click(function(){
        var parent = $(this).parents(\'.switch\');
        $(\'.cb-enable\',parent).removeClass(\'selected\');
        $(this).addClass(\'selected\');
        $(\'.checkbox\',parent).attr(\'checked\', false);
        var menuId = $(this).attr("id");
        $.ajax({
          url: "'.$RG_addr["utenti_superpoteri"].'",
          cache: false,
          type: "POST",
          data: {   id_utente :"'.mimmo_encode($id_utente).'",
                    do: "save", 
                    id : menuId},
          dataType: "html"
        }).done(function( html ) {
          $($(parent).parent()).removeClass(\'sel\');  
          $($(parent).parent()).addClass(\'desel\');
          $(\'#result\').html( html);
        });
    });
});
</script>

    <style type="text/css">
    
    .field { width: 100%; float: left; margin: 0 0 20px; }
    .field input { margin: 0 0 0 20px; }
    .sel {background-color:rgba(00,200,20,.1);}
    .desel {background-color:rgba(200,00,20,.1);}
    
    /* Used for the Switch effect: */
    .field_container {height:3em; padding:.8em;margin:1em;border:solid 1px #ccc;display:block;}
    .field_descriptor {margin-left:2em; font-size:2em;}
    .cb-enable, .cb-disable, .cb-enable span, .cb-disable span { background: url(switch.gif) repeat-x; display: block; float: left; }
    .cb-enable span, .cb-disable span { line-height: 30px; display: block; background-repeat: no-repeat; font-weight: bold; }
    .cb-enable span { background-position: left -90px; padding: 0 10px; }
    .cb-disable span { background-position: right -180px;padding: 0 10px; }
    .cb-disable.selected { background-position: 0 -30px; }
    .cb-disable.selected span { background-position: right -210px; color: #fff; }
    .cb-enable.selected { background-position: 0 -60px; }
    .cb-enable.selected span { background-position: left -150px; color: #fff; }
    .switch label { cursor: pointer; }
    </style>
';



if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Gestioni superpoteri utente ".fullname_from_id($id_utente)."</h3>";

if(leggi_permessi_utente($id_utente) & perm::puo_eliminare_messaggi){
    $b_state="sel";
    $b_ok ="selected";
    $b_no ="";
}else{
    $b_state="desel";
    $b_no = "selected";
    $b_ok = "";
}

// GESTIONE BACHECA
$h .= "<div class=\"field_container ui-corner-all $b_state\">";
$h .= "<p class=\"field switch\">";
$h .= "<span class=\"field_descriptor\">La gestione della bacheca</span>";
$h .= "<label class=\"cb-enable $b_ok\" id=\"sp_bacheca_on\"><span>CONSENTI</span></label>";
$h .= "<label class=\"cb-disable $b_no\" id=\"sp_bacheca_off\"><span>NEGA</span></label>";
$h .= "<input type=\"checkbox\" id=\"cb_bacheca\" class=\"checkbox\" name=\"bacheca\" style=\"display:none\">";
$h .= "</p>";
$h .= "</div>";


if(leggi_permessi_utente($id_utente) & perm::puo_gestire_la_cassa){
    $c_state="sel";
    $c_ok ="selected";
    $c_no ="";
}else{
    $c_state="desel";
    $c_no = "selected";
    $c_ok = "";
}

// GESTIONE CASSA
$h .= "<div class=\"field_container ui-corner-all $c_state\">";
$h .= "<p class=\"field switch\">";
$h .= "<span class=\"field_descriptor\">La gestione della CASSA</span>";
$h .= "<label class=\"cb-enable $c_ok\" id=\"sp_cassa_on\"><span>CONSENTI</span></label>";
$h .= "<label class=\"cb-disable $c_no\" id=\"sp_cassa_off\"><span>NEGA</span></label>";
$h .= "<input type=\"checkbox\" id=\"cb_cassa\" class=\"checkbox\" name=\"cassa\" style=\"display:none\">";
$h .= "</p>";
$h .= "</div>";





// GESTIONE PERMESSI UTENTI
if(leggi_permessi_utente($id_utente) & perm::puo_mod_perm_user_gas){
    $pu_state="sel";
    $pu_ok ="selected";
    $pu_no ="";
}else{
    $pu_state="desel";
    $pu_no = "selected";
    $pu_ok = "";
}
$h .= "<div class=\"field_container ui-corner-all $pu_state\">";
$h .= "<p class=\"field switch\">";
$h .= "<span class=\"field_descriptor\">La gestione degli UTENTI (Permessi)</span>";
$h .= "<label class=\"cb-enable $pu_ok\" id=\"sp_permutenti_on\"><span>CONSENTI</span></label>";
$h .= "<label class=\"cb-disable $pu_no\" id=\"sp_permutenti_off\"><span>NEGA</span></label>";
$h .= "<input type=\"checkbox\" id=\"cb_permutenti\" class=\"checkbox\" name=\"permutenti\" style=\"display:none\">";
$h .= "</p>";
$h .= "</div>";

// GESTIONE GAS
if(leggi_permessi_utente($id_utente) & perm::puo_creare_gas){
    $g_state="sel";
    $g_ok ="selected";
    $g_no ="";
}else{
    $g_state="desel";
    $g_no = "selected";
    $g_ok = "";
}
$h .= "<div class=\"field_container ui-corner-all $g_state\">";
$h .= "<p class=\"field switch\">";
$h .= "<span class=\"field_descriptor\">La gestione del proprio GAS</span>";
$h .= "<label class=\"cb-enable $g_ok\" id=\"sp_gas_on\"><span>CONSENTI</span></label>";
$h .= "<label class=\"cb-disable $g_no\" id=\"sp_gas_off\"><span>NEGA</span></label>";
$h .= "<input type=\"checkbox\" id=\"cb_utenti\" class=\"checkbox\" name=\"gas\" style=\"display:none\">";
$h .= "</p>";
$h .= "</div>";


// GESTIONE UTENTI
if(leggi_permessi_utente($id_utente) & perm::puo_gestire_utenti){
    $u_state="sel";
    $u_ok ="selected";
    $u_no ="";
}else{
    $u_state="desel";
    $u_no = "selected";
    $u_ok = "";
}
$h .= "<div class=\"field_container ui-corner-all $u_state\">";
$h .= "<p class=\"field switch\">";
$h .= "<span class=\"field_descriptor\">La gestione degli UTENTI (Iscrizioni)</span>";
$h .= "<label class=\"cb-enable $u_ok\" id=\"sp_utenti_on\"><span>CONSENTI</span></label>";
$h .= "<label class=\"cb-disable $u_no\" id=\"sp_utenti_off\"><span>NEGA</span></label>";
$h .= "<input type=\"checkbox\" id=\"cb_utenti\" class=\"checkbox\" name=\"utenti\" style=\"display:none\">";
$h .= "</p>";
$h .= "</div>";


if(leggi_permessi_utente($id_utente) & perm::puo_vedere_tutti_ordini){
    $o_state="sel";
    $o_ok ="selected";
    $o_no ="";
}else{
    $o_state="desel";
    $o_no = "selected";
    $o_ok = "";
}

// GESTIONE ORDINI
$h .= "<div class=\"field_container ui-corner-all $o_state\">";
$h .= "<p class=\"field switch\">";
$h .= "<span class=\"field_descriptor\">La gestione avanzata degli ORDINI</span>";
$h .= "<label class=\"cb-enable $o_ok\" id=\"sp_ordini_on\"><span>CONSENTI</span></label>";
$h .= "<label class=\"cb-disable $o_no\" id=\"sp_ordini_off\"><span>NEGA</span></label>";
$h .= "<input type=\"checkbox\" id=\"cb_ordini\" class=\"checkbox\" name=\"ordini\" style=\"display:none\">";
$h .= "</p>";
$h .= "</div>";


$h .= "<div id=\"result\"></div>";

$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);