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


if(!isset($id_ordine)){
    go("sommario_mobile"); 
}
(int)$id_ordine;

//IMPOSTO le cose uguali per tutte le pagine_
$footer_title = _USER_FULLNAME.", ".gas_nome(_USER_ID_GAS);

       
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());
//$j->jqm_extra_css[]="<link rel=\"stylesheet\" href=\"http://jeromeetienne.github.com/jquery-mobile-960/css/jquery-mobile-960.min.css\" />";

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param("RG Ordini","scheda"));
$p->jqm_footer_hide= true;
//Assegno la navbar

$n = new jqm_navbar(load_scheda_navbar(null,$id_ordine));
$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");

$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno la sotto navbar
//$n_s = new jqm_navbar(array(""));
//$p->jqm_header_navbar_sub=$n_s->jqm_render_navbar();
//Inserisco i contenuti
$p->jqm_page_content =schedona_ordine_mobile($id_ordine,_USER_ID);
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>