<?php   

$_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas",
                                "listini",
                                "ditte");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}


if(!_USER_PERMISSIONS & perm::puo_gestire_retegas){
    go("sommario_mobile"); 
}



//IMPOSTO le cose uguali per tutte le pagine_
$footer_title = _USER_FULLNAME.", ".gas_nome(_USER_ID_GAS);

       
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param("ReteGas.AP","admin_panel"));

//Negli attributi assegno un ID
$p->jqm_footer_hide= true;


//-----------------------------ATTIVITA
$ultima_attivita ="";
$query = "SELECT * FROM maaking_users ORDER BY last_activity DESC LIMIT 10;";
$res = $db->sql_query($query);
$ua = "<ul data-role=\"listview\" data-inset=\"true\">";
while ($row = mysql_fetch_array($res)){
    $ua .= "<li><a href=\"".$RG_addr["m_user_scheda"]."?id_utente=".mimmo_encode($row["userid"])."\">";
    $ua .= "<h4>".$row["fullname"]."</h4>
            <p><strong>".gas_nome($row["id_gas"])."</strong></p>";
    $ua .= "<p>".conv_datetime_from_db($row["last_activity"])."</p>";
    $ua .= "</a></li>";
}
$ua .= "</ul>";

//---------------------------TUTTI UTENTI
$tu ="";
$query = "SELECT * FROM maaking_users;";
$res = $db->sql_query($query);
$tu = "<ul data-role=\"listview\"  data-filter=\"true\" data-inset=\"true\">";
while ($row = mysql_fetch_array($res)){
    $tu .= "<li><a href=\"".$RG_addr["m_user_scheda"]."?id_utente=".mimmo_encode($row["userid"])."\">";
    $tu .= "<h4>".$row["fullname"]."</h4>";
    $tu .= "<p>".gas_nome($row["id_gas"])."</p>";
    $tu .= "</a></li>";
}
$tu .= "</ul>";



$soldoni = totale_retegas_netto();
$res = $db->sql_query("SELECT * FROM maaking_users WHERE isactive=1;");
$quanti = $db->sql_numrows($res);
$online = crea_numero_user_attivi_totali(2); 

$h .="<div data-role=\"collapsible-set\" data-content-theme=\"c\">
        
        <div data-role=\"collapsible\" data-collapsed=\"false\">
        <h3>Retegas</h3>
                <p>Users : <strong>$quanti</strong></p>
                <p>Totale : <strong>$soldoni</strong></p>
                <p>Online : <strong>$online</strong></p>
            
        </div>
        
        <div data-role=\"collapsible\" >
        <h3>Ultima attività</h3>
        <p>$ua</p>
        </div>
        
        <div data-role=\"collapsible\" >
        <h3>Tutti gli utenti</h3>
        <p>$tu</p>
        </div>
        
    </div>";



if(_USER_PERMISSIONS & perm::puo_gestire_la_cassa){
    $p->jqm_page_content = $h;
}else{
    $p->jqm_page_content = "Non sei abilitato per questa pagina";
}
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1




//La visualizzo
echo $j->jqm_render();
unset($j);  
?>