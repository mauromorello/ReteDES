<?php
  if (eregi("ordini_chiusi_menu_core.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

if($_parenDir=="ordini_chiusi"){
    $pa = "../";
}else{
    $pa="";
}
require_once("$pa../ordini/ordini_renderer.php");

if(isset($gas)){$id_gas=$gas;};

// ------------------------------------------------STAMPA
if(is_printable_from_id_ord($id) or (id_referente_ordine_globale($id)==$id_user)){
if(isset($pdf_url) or isset($display_url)){
$h_menu2 .='<li><a class="medium silver awesome"><b>Stampa</b></a>'; 
$h_menu2 .='<ul>';
     if(isset($pdf_url)){ 
     $h_menu2 .='<li><a class="medium silver awesome" href="'.$pdf_url.'" target="_self">Salva come PDF</a></li>';
                
     }
     if(isset($display_url)){
     $h_menu2 .='<li><a class="medium silver awesome" href="'.$display_url.'" target="_blank">Versione stampabile</a></li>';      
         
     }
     
     if(isset($excel_url)){
     $h_menu2 .='<li><a class="medium silver awesome" href="'.$excel_url.'" target="_self">Foglio Excel</a></li>';               
     }
     if(isset($word_url)){
     $h_menu2 .='<li><a class="medium silver awesome" href="'.$word_url.'" target="_self">Documento Word</a></li>';               
     }
     
     
$h_menu2 .='</ul>';
$h_menu2 .='</li>'; 
}
}
// ------------------------------------------------FINE STAMPA




$h_table .= '
<div style="padding-bottom:2em">
<ul class="sf-menu">'.
ordini_menu_pacco($id).
$h_menu2.   
ordini_menu_visualizza($user,$id).                                                                           
ordine_menu_mia_spesa($id_user,$id).
ordine_menu_gas($id_user,$id,$id_gas).
ordine_menu_gestisci_new($id_user,$id,$id_gas).
ordine_menu_comunica($id_user,$id,$id_gas).
'</ul>
</div>
</br>';


?>