<?php   

$_FUNCTION_LOADER=array("mobile",
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


if(!isset($id_ditta)){
    go("sommario_mobile"); 
}
(int)$id_ditta;

//IMPOSTO le cose uguali per tutte le pagine_
$footer_title = _USER_FULLNAME.", ".gas_nome(_USER_ID_GAS);

       
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param("RG-Ditta","scheda_ditta"));

//Negli attributi assegno un ID
$p->jqm_footer_hide= true;

//Assegno la navbar
//Con solo un pulsate per tornare indietro
$n = new jqm_navbar(load_scheda_ditta_navbar(null,$id_ditta));
$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");

//$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");
$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$p->jqm_page_content = schedona_ditta_mobile($id_ditta);
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>