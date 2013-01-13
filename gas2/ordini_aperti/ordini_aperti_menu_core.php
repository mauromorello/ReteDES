<?php



  if (eregi("ordini_aperti_menu_core.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

if($_parenDir=="ordini_aperti"){
	$pa = "../";
}else{
	$pa="";
}
require_once("$pa../ordini/ordini_renderer.php");



$h_table .= '
<div style="padding-bottom:2em">
<ul class="sf-menu">'.
ordini_menu_pacco($id).
ordini_menu_visualizza($user,$id).
ordine_menu_operazioni_base($id_user,$id).
ordine_menu_mia_spesa($id_user,$id).
ordine_menu_gas($id_user,$id,$id_gas).
ordine_menu_gestisci_new($id_user,$id,$id_gas).
ordine_menu_comunica($id_user,$id,$id_gas).
'</ul>
</div>
</br>';



?>