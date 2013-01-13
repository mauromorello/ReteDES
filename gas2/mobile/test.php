<?php   $_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}

//Contenuti

$f_1 = new jqm_form(array(  "id"=>"nuova",
                            "attribs"=>"data-ajax=\"false\""));
                            
$text_item = new jqm_form_text(array(    "name"=>"ciccio",
                                         "value"=>$ciccio));
                            
$f_1->items[]= $text_item->create_form_text_item(); 

$c = $f_1->render_form();       
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param());
//Negli attributi assegno un ID
$p->jqm_page_attrib="id=\"aperti\"";
$p->jqm_footer_hide= true;
//Assegno la navbar

$n = new jqm_navbar(load_ordini_navbar());
$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");
$p->jqm_header_navbar=$n->jqm_render_navbar();
//Inserisco i contenuti
$p->jqm_page_content =$c;
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1

//La visualizzo
echo $j->jqm_render();
unset($j);  
?>