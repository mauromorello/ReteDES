<?php   $_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas",
                                "listini",
                                "cassa",
                                "ditte",
                                "tipologie",
                                "geocoding");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}

if(!(_USER_USA_CASSA)){
    go("sommario_mobile");              
}


//Mia spesa
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());    
    
    $l = new jqm_list();
    $l->jqm_list_attrib[] = " data-inset=\"true\" ";
    
    $id_listini = id_listino_from_id_ordine($id_ordine);
    
    $query = "SELECT * FROM maaking_users WHERE isactive='1';";
    $res = $db->sql_query($query);
    while ($row = mysql_fetch_array($res)){
        
        if(read_option_text($row["userid"],"_USER_USA_CASSA")=="SI"){
            $l->jqm_list_items[]="<li>
                                  <a href=\"".$RG_addr["m_user_scheda"]."?id_utente=".mimmo_encode($row["userid"])."\">
                                  ".$row["fullname"]."
                                  <hr>
                                  <p>Saldo : "._nf(cassa_saldo_utente_totale($row["userid"]))." Eu.</P>
                                  
                                  </a>
                                  <a href=\"".$RG_addr["m_cassa_user_mov"]."?id_utente=".mimmo_encode($row["userid"])."\">Movimenti</a>
                                  </li>";
        }
    }    
    $mov_2 = $l->jqm_list_render();
    unset($l);



//-------------------------------------------------------PAG 2                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param());
//Negli attributi assegno un ID
$p->jqm_page_attrib="id=\"utenti_cassati\"";
$p->jqm_footer_hide= true;
//Assegno la navbar

//$n = new jqm_navbar(load_scheda_navbar(null,$id_ordine));
//$n->jqm_navbar_set_item_attrib(2,"class=\"ui-btn-active ui-state-persist\"");
//$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$p->jqm_page_content = "<h3>Utenti con cassa attiva</h3>
                       $mov_2";
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 2



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>