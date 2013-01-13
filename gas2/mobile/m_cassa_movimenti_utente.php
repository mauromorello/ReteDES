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

if(isset($id_utente)){
    $id_utente=mimmo_decode($id_utente);
}else{
    $id_utente = _USER_ID;
}

//Mia spesa
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());    
    
    $l = new jqm_list();
    $l->jqm_list_attrib[] = " data-inset=\"true\" ";

    
    $query = "SELECT * FROM retegas_cassa_utenti WHERE id_utente='$id_utente' ORDER BY id_cassa_utenti DESC;";
    $res = $db->sql_query($query);
    while ($row = mysql_fetch_array($res)){
        
        if($row["registrato"]=="si"){
            $pal = '<IMG SRC="'.$RG_addr["icn_pallino_verde"].'" class="ui-li-icon">';
        }else{
            $pal = '<IMG SRC="'.$RG_addr["icn_pallino_grigio"].'" class="ui-li-icon">';
        }
        
        
        $l->jqm_list_items[]="<li>
                              <a>$pal
                              <span style=\"font-size:.75em;font-weight:normal\">(".$row["id_cassa_utenti"].")</span> ".$row["segno"]." "._nf($row["importo"])." Eu. 
                              <br>
                              ".$row["descrizione_movimento"]."
                              <div>  
                              <span style=\"font-size:.75em;font-weight:normal\">Ordine : ".descrizione_ordine_from_id_ordine($row["id_ordine"])."</span><br>
                              <span style=\"font-size:.75em;font-weight:normal\">".$__movcas[$row["tipo_movimento"]]."
                              <br>
                              Inserito da : ".fullname_from_id($row["id_cassiere"])."</span></div>
                              </a>
                              </li>";

    }    
    $mov_2 = $l->jqm_list_render();
    unset($l);



//-------------------------------------------------------PAG 2                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param());
//Negli attributi assegno un ID
$p->jqm_page_attrib="id=\"movimenti utente\"";
$p->jqm_footer_hide= true;
//Assegno la navbar

//$n = new jqm_navbar(load_scheda_navbar(null,$id_ordine));
//$n->jqm_navbar_set_item_attrib(2,"class=\"ui-btn-active ui-state-persist\"");
//$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$p->jqm_page_content = "<h3>Movimenti cassa di ".fullname_from_id($id_utente)."</h3>
                        <p>Saldo : ".cassa_saldo_utente_totale($id_utente)." Eu.</p>".
                       $mov_2;
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 2



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>