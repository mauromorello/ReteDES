<?php   $_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas",
                                "listini",
                                "ditte",
                                "tipologie",
                                "geocoding");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}

if(!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
    go("sommario_mobile");              
}

if(!isset($id_ordine)){
    go("sommario_mobile"); 
}
(int)$id_ordine;





//Mia spesa
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());    
    
    $l = new jqm_list();
    $l->jqm_list_attrib[] = " data-inset=\"true\" ";
    
    $id_listini = id_listino_from_id_ordine($id_ordine);
    
    $query = "SELECT * FROM retegas_articoli WHERE id_listini='$id_listini';";
    $res = $db->sql_query($query);
    while ($row = mysql_fetch_array($res)){
    $val_mio = valore_netto_arr_articolo_ordine_user($row["id_articoli"],$id_ordine,_USER_ID);
    if($val_mio>0){
        
        $split="";
        $dt = "data-theme=\"e\" ";
        $l->jqm_list_items[]="
        <li $dt>
            <a  href=\"".$RG_addr["m_ordini_partecipa_art"]."?id_ordine=$id_ordine&id_articolo=".$row["id_articoli"]."\">
                <h6>".$row["codice"]." - ".$row["descrizione_articoli"]."</h6>
                <p>".$row["u_misura"]." ".$row["misura"]." x Eu. ".$row["prezzo"]."</p> 
                <h4>In Ordine: $val_mio Eu.</h4>
            </a>
        </li>";
        
        
    }else{
        
        $split="";
        $dt="";
    }
        
    
    }    
    $cont_2 = $l->jqm_list_render();
    unset($l);



//-------------------------------------------------------PAG 2                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param());
//Negli attributi assegno un ID
$p->jqm_page_attrib="id=\"mia_spesa\"";
$p->jqm_footer_hide= true;
//Assegno la navbar

$n = new jqm_navbar(load_scheda_navbar(null,$id_ordine));
$n->jqm_navbar_set_item_attrib(2,"class=\"ui-btn-active ui-state-persist\"");
$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$p->jqm_page_content = schedona_ordine_mobile($id_ordine,_USER_ID).
                       "<h3>Articoli ordinati</h3>".
                       $cont_2;
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 2



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>