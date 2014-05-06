<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//questa pagina la può vedere solo il responsabile DES e io
if(!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
    if((_USER_ID <> db_val_q("id_des",_USER_ID_DES,"id_referente","retegas_des"))){
        pussa_via();
     }
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
if($do=="do_des_lat"){
    $sql = "UPDATE retegas_des SET des_lat='".CAST_TO_FLOAT($des_lat)."' WHERE id_des='"._USER_ID_DES."' LIMIT 1;";
    $res = $db->sql_query($sql);
    sleep(1);
    $msg="Latitudine modificata";
    go("des_option_sito",_USER_ID,$msg);
}
if($do=="do_des_lng"){
    $sql = "UPDATE retegas_des SET des_lng='".CAST_TO_FLOAT($des_lng)."' WHERE id_des='"._USER_ID_DES."' LIMIT 1;";
    $res = $db->sql_query($sql);
    sleep(1);
    $msg="Longitudine modificata";
    go("des_option_sito",_USER_ID,$msg);
}
if($do=="do_des_zoom"){
    $sql = "UPDATE retegas_des SET des_zoom='".CAST_TO_INT($des_zoom,0,15)."' WHERE id_des='"._USER_ID_DES."' LIMIT 1;";
    $res = $db->sql_query($sql);
    sleep(1);
    $msg="Zoom modificato";
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
//DES LAT
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Latitudine</h3>
        <label for=\"des_lat\">per centrare la mappa des, ricavarla da googlemaps</label>
        <input type=\"text\" id=\"des_lat\"  name=\"des_lat\" value=\""._USER_DES_LAT."\" size=\"50\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_des_lat\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>        
      </div>";
//DES LNG
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Longitudine</h3>
        <label for=\"des_lng\">per centrare la mappa des, ricavarla da googlemaps</label>
        <input type=\"text\" id=\"des_lng\"  name=\"des_lng\" value=\""._USER_DES_LNG."\" size=\"50\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_des_lng\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>        
      </div>";
//DES ZOOM
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Zoom mappa</h3>
        <label for=\"des_zoom\">Quantità di zoom (1 lontano <--> 15 vicino)</label>
        <input type=\"text\" id=\"des_zoom\"  name=\"des_zoom\" value=\""._USER_DES_ZOOM."\" size=\"50\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_des_zoom\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>        
      </div>";            
      
 //DES CODICE
$h2 .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Codice per Wordpress</h3>
        <label for=\"des_code\">Il codice da usare per il widget wordpress</label>
        <input type=\"text\" id=\"des_code\"  name=\"des_code\" value=\"5sdfon32r098jwerdxd2io3lnn\" size=\"50\" readonly=\"readonly\"></input>
        <input type=\"hidden\" name=\"do\" value=\"do_des_code\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>        
      </div>";     
      
      
      
      



//$r->contenuto = rg_toggable("Alcune novità","poio",$bla,false).$h;
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);