<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if($do=="do_site_logo"){
    write_option_gas_text(_USER_ID_GAS,"_GAS_SITE_LOGO",sanitize($site_logo));
    sleep(1);
    $msg="Logo modificato correttamente";
}
if($do=="do_site_inattivita"){
    write_option_gas_text(_USER_ID_GAS,"_GAS_SITE_INATTIVITA",CAST_TO_INT($site_inattivita,0,60));
    sleep(1);
    $msg="Giorni inattività modificati correttamente";
}
if($do=="do_site_frase_inattivita"){
    write_option_gas_text(_USER_ID_GAS,"_GAS_SITE_FRASE_INATTIVITA",sanitize($site_frase_inattivita));
    sleep(1);
    $msg="Frase inattività modificata correttamente";
}
if($do=="do_gas_cassa"){
    if(sanitize($gas_usa_cassa)<>"SI"){
        $gas_usa_cassa = "NO";
    }
    write_option_gas_text(_USER_ID_GAS,"_GAS_USA_CASSA",$gas_usa_cassa);
    sleep(1);
    $msg="Impostazione CASSA modificata.";
    log_me(0,_USER_ID,"OPT","GAS","GAS "._USER_ID_GAS." _GAS_USA_CASSA : $gas_usa_cassa",_USER_ID_GAS,null);
}

if($do=="do_gas_puo_partecipare_ordini_esterni"){
    if(sanitize($gas_puo_partecipare_ordini_esterni)<>"SI"){
        $gas_puo_partecipare_ordini_esterni = "NO";
    }
    write_option_gas_text(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST",$gas_puo_partecipare_ordini_esterni);
    sleep(1);
    $msg="Impostazione modificata";
}

if($do=="do_gas_condivisione_propri_ordini"){
    if(sanitize($gas_condivisione_propri_ordini)<>"SI"){
        $gas_condivisione_propri_ordini = "NO";
    }
    write_option_gas_text(_USER_ID_GAS,"_GAS_PUO_COND_ORD_EST",$gas_condivisione_propri_ordini);
    sleep(1);
    $msg="Impostazione modificata";
}


//SHOW USERID
if($do=="do_site_show_userid"){
    if(sanitize($site_show_userid)<>"SI"){
        $site_show_userid = "NO";
    }
    write_option_gas_text(_USER_ID_GAS,"_SITE_SHOW_USERID",$site_show_userid);
    sleep(1);
    $msg="Impostazione modificata";
}

//HASHTAG TWEET
if($do=="do_hashtag_gas"){
    write_option_gas_text_new(_USER_ID_GAS,"_HASHTAG_GAS",sanitize(substr(trim($hashtag_gas),0,4)));
    sleep(1);
    $msg="Impostazione modificata";
}
//WPID
if($do=="do_wpid_gas"){
    write_option_gas_text_new(_USER_ID_GAS,"_WPID_GAS",sanitize(substr(trim($wpid_gas),0,10)));
    sleep(1);
    $msg="Impostazione modificata";
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva =  menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opzioni GAS";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = gas_menu_completo($user);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//--------------------------------------------CONTENUTO
     


//LOGO
$h2 .= "<div class=\"rg_widget rg_widget_helper\">
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <h3>Logo del sito</h3>
        <label for=\"site_logo\">Il logo del proprio Gas</label>
        <input id=\"site_logo\" DISABLED name=\"site_logo\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_SITE_LOGO")."\" size=\"40\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_logo\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong> 
        <p>Il logo verrà ridimensionato a 75x75 pixels</p>
        <p>Immettere l'URL del logo. Per il momento non è possibile caricare su retegas il file originale.</p> 
        </div>
      
      </div>";

//GG inattivit?
$h .= "<div class=\"rg_widget rg_widget_helper\">
        <h2>Gestione sospensione utenti</h2>
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.nextme.it/images/stories/Rubriche/LoSapeviChe/pigrizia.jpg\" style=\"width:80px;height:40px\">        

        <label for=\"site_inattivita\">Giorni di inattività per essere sospesi (MAX 60)</label>
        <input type=\"number\" id=\"site_inattivita\"  name=\"site_inattivita\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_SITE_INATTIVITA")."\" size=\"3\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_inattivita\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>

        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://avvertenze.aduc.it/generale/files/image/2010/gennaio/sgridata.png\" style=\"width:40px;height:40px\">        

        <label for=\"site_frase_inattivita\">Frase che compare quando un utente sospeso in automatico si connette la prossima volta.</label>
        <input id=\"site_frase_inattivita\"  name=\"site_frase_inattivita\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_SITE_FRASE_INATTIVITA")."\" size=\"40\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_frase_inattivita\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
        
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong> 
        <p>Il sito controlla giornalmente da quanto tempo ogni utente non ha più effettuato un accesso.</p>
        <p>Superato questo limite (max 60 gg) l'utente viene \"sospeso\", in modo da rendere necessario l'intervento del \"gestore utenti\" per riattivarlo.</p> 
        <p>Se non viene impostata nessuna frase di avviso, verrà proposta quella standard \"Account sospeso per prolungata inattività\"; La frase di avviso è impostabile singolarmente dalla scheda 
        di ogni utente. Dalla stessa scheda è possibile riattivarlo o cancellarlo definitivamente.</p>
        <p>La frase di avviso comparirà all'utente la prima volta che tenta di accedere al sito.</p>
        </div>
        
      </div>";

      
      
      
      
//Uso cassa
$h .= "<div class=\"rg_widget rg_widget_helper\">
        
        <h2>Gestione cassa</h2>
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://1.bp.blogspot.com/_NEUwANMK3tU/TDxcfgikLNI/AAAAAAAAAAk/tLJRe6Zzt64/s320/diventarericchi.jpg\" style=\"width:40px;height:40px\">        
        <label for=\"site_cassa\">Imposta in questo campo il valore (SI/NO) che serve a retegas per far comparire o nascondere tutti i menu della cassa.</label>
        <input id=\"site_cassa\"  name=\"gas_usa_cassa\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_USA_CASSA")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_cassa\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      
      </div>";      

//ID utente nella barra info
$h .= "<div class=\"rg_widget rg_widget_helper\">
        
        <h2>Mostra ID utente nella barra info</h2>
        
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.dmv.state.pa.us/graphics/PA_VOTER_ID_LAMINATE.jpg\" style=\"width:40px;height:40px\">        

        <label for=\"site_show_userid\">Vuoi vedere l'user_id nella barra info ? (SI/NO)</label>
        <input id=\"site_show_userid\"  name=\"site_show_userid\" value=\"".read_option_gas_text(_USER_ID_GAS,"_SITE_SHOW_USERID")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_site_show_userid\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      
      </div>";

//Hashtag GAS
$h .= "<div class=\"rg_widget rg_widget_helper\">
        
        <h2>Hashtag GAS</h2>
        
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.zeusnews.it/img/4/3/7/7/1/0/017734-twitter.jpg\" style=\"width:40px;height:40px\">
        <label for=\"hashtag_gas\">Hashtag che deve essere incluso nei tweet generati dal tuo gas. MAX 4 CARATTERI</label>
        <input id=\"hashtag_gas\"  name=\"hashtag_gas\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_HASHTAG_GAS")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_hashtag_gas\"></input>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        <strong>Attenzione :</strong> 
        <p>Immettere l'hashtag SENZA CANCELLETTO</p> 
        </div>
      </div>";
      
//Wordpress ID
$h .= "<div class=\"rg_widget rg_widget_helper\">
        
        <h2>Wordpress ID</h2>
        
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.ognigiorno.com/wp-content/uploads/2011/05/wordpress-logo.png\" style=\"width:40px;height:40px\">
        <label for=\"wpid_gas\">Codice da usare per i WIDGET di WordPress.</label>
        <input id=\"wpid_gas\"  name=\"wpid_gas\" value=\"".read_option_gas_text_new(_USER_ID_GAS,"_WPID_GAS")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_wpid_gas\"></input>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
      </div>";                  

//Partecipazione ordini esterni
$h .= "<div class=\"rg_widget rg_widget_helper\">
        
        <h2>Gestione Ordini</h2>
        
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://3.bp.blogspot.com/-mpssSKD10EY/TxdkXqLkMbI/AAAAAAAAAlM/_5GYwoQ0HDY/s320/receivinggift.jpg\" style=\"width:40px;height:40px\">        
        <label for=\"gas_puo_partecipare_ordini_esterni\">Il proprio gas può partecipare agli ordini esterni condivisi ? (SI/NO)</label>
        <input id=\"gas_puo_partecipare_ordini_esterni\"  name=\"gas_puo_partecipare_ordini_esterni\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_puo_partecipare_ordini_esterni\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form>
        
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        <img SRC=\"http://www.beacon.it/wordpress/wp-content/uploads/2012/08/cassetta-150x150.jpg\" style=\"width:40px;height:40px\"> 
        <label for=\"gas_condivisione_propri_ordini\">Il proprio gas può condividere i propri ordini ai GAS esterni ? (SI/NO)</label>
        <input id=\"gas_condivisione_propri_ordini\"  name=\"gas_condivisione_propri_ordini\" value=\"".read_option_gas_text(_USER_ID_GAS,"_GAS_PUO_COND_ORD_EST")."\" size=\"10\" ></input>
        <input type=\"hidden\" name=\"do\" value=\"do_gas_condivisione_propri_ordini\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        </form> 
              
      </div>";                  

      
//-----------------------------------------------------


//$r->contenuto = rg_toggable("Alcune novit?","poio",$bla,false).$h;
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);