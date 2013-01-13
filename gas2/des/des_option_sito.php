<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     pussa_via();
}

if($do=="do_des_logo"){
    write_option_des_text(_USER_ID_DES,"_DES_SITE_LOGO",sanitize($site_logo));
    sleep(1);
    $msg="Logo modificato";
}

if($do=="do_des_nome"){
    $sql = "UPDATE retegas_des SET des_descrizione='".CAST_TO_STRING($des_nome,30)."' WHERE id_des='"._USER_ID_DES."' LIMIT 1;";
    $res = $db->sql_query($sql);
    sleep(1);
    $msg="Nome modificato";
    go("des_option_sito",_USER_ID,$msg);
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva =  menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opzioni DES";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = des_menu_completo(_USER_ID);

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Creo la pagina dell'aggiunta

//--------------------------------------------CONTENUTO
     


//LOGO
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Logo del DES</h3>
        <img SRC=\"".read_option_des_text(_USER_ID_DES,"_DES_SITE_LOGO")."\" height=\"75\" width=\"75\">
        <label for=\"site_logo\">Il logo del proprio DES</label>
        <input id=\"site_logo\"  name=\"site_logo\" value=\"".read_option_des_text(_USER_ID_DES,"_DES_SITE_LOGO")."\" size=\"50\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_des_logo\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong> 
        <p>Il logo verrà ridimensionato a 75x75 pixels</p>
        <p>Immettere l'URL del logo. Per il momento non è possibile caricare su retegas il file originale.</p> 
        </div>
      
      </div>";

//DES nome
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Nome del DES</h3>
        <label for=\"des_nome\">Nome del proprio des (compare in alto a destra e in alcune pagine, max 30 caratteri)</label>
        <input type=\"text\" id=\"des_nome\"  name=\"des_nome\" value=\""._USER_DES_NAME."\" size=\"50\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_des_nome\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>        
      </div>";

 //DES CODICE
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Codice per Wordpress</h3>
        <label for=\"des_code\">Il codice da usare per il widget wordpress</label>
        <input type=\"text\" id=\"des_code\"  name=\"des_code\" value=\"5sdfon32r098jwerdxd2io3lnn\" size=\"50\" readonly=\"readonly\"></input>
        <input type=\"hidden\" name=\"do\" value=\"do_des_code\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>        
      </div>";     
      
      
      
//Uso cassa
$h2 .= "<div class=\"rg_widget rg_widget_helper\">
        
        <h2>Gestione cassa</h2>
        
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Il tuo gas usa la cassa ?</h3>
        <label for=\"site_cassa\">Imposta in questo campo il valore (SI/NO) che serve a retegas per far comparire o nascondere tutti i menù della cassa.</label>
        <input id=\"site_cassa\"  name=\"gas_usa_cassa\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_USA_CASSA")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      
      </div>";      



//$r->contenuto = rg_toggable("Alcune novità","poio",$bla,false).$h;
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);