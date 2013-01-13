<?php
  if (eregi("ditte_menu_core.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

// $h_table = Content
// $id_user = utente
// $id = ordine

$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

if($_parenDir=="ditte"){
	$pa = "../";
}else{
	$pa="";
}

if($_parenDir=="listini"){
	$pa = "../";
}else{
	$pa="";
}

$h_menu ='
<div style="padding-bottom:2em;">
<ul class="sf-menu">';  // INIZIO


if ($my_user_level>=0){       // SOLO SE E' un utente autorizzato
	
// NUOVA ORDINE
if (isset($nuova_ditta)){
		$h_menu .='<li><a class="medium blue awesome" href="'.$pa.'ditte_form_add.php"><b>Nuova Ditta</b></a></li>';
}   
//NUOVO ORDINE
// NUOVA ORDINE
if (isset($nuovo_listino)){
		$h_menu .='<li><a class="medium blue awesome" href="../listini/listini_form_add.php?id='.$id.'"><b>Nuovo Listino</b></a></li>';
}   
//NUOVO ORDINE


}

// ------------------------------------------------VISUALIZZA


		   
	$h_menu .='<li><a class="medium green awesome"><b>Visualizza</b></a>'; 
	$h_menu .='<ul>';
  
		 

	$h_menu .='<li><a class="medium green awesome" href="'.$pa.'ditte_table.php" target="_self">Tutte le ditte</a></li>';
	$h_menu .='<li><a class="medium green awesome" href="'.$pa.'ditte_table_mie.php" target="_self">Mie Ditte</a></li>';
	   
	$h_menu .='</ul>';
	$h_menu .='</li>';
   

// ------------------------------------------------VISUALIZZA

 

// ------------------------------------------------Operazioni

if ($my_user_level>=0){
	if (isset($operazioni_consentite)){
		if(ditta_user($id)==$id_user){		
			$h_menu .='<li><a class="medium yellow awesome"><b>Operazioni</b></a>'; 
			$h_menu .='<ul>';
		  
				 
				 
					$h_menu .='<li><a class="medium yellow awesome" href="'.$pa.'ditte_form_edit.php?id='.$id.'" target="_self">Modifica questa ditta</a></li>';
					if(listini_ditte($id)==0){ 
						$h_menu .='<li><a class="medium red awesome" href="'.$pa.'ditte_form_delete.php?id='.$id.'" target="_self">Elimina questa ditta</a></li>';
					}
				
			$h_menu .='</ul>';
			$h_menu .='</li>';
	}
	}	 
}
// ------------------------------------------------OPERAZIONI

 



$h_menu .=''; 
$h_menu .=''; 
$h_menu .='</ul>
		   </div>
		   <br />  
			';                // FINE





$h_table=$h_menu;


?>
