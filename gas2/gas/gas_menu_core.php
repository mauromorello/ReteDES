<?php
  if (eregi("gas_menu_core.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

// $h_table = Content
// $id_user = utente
// $id = ordine

$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

if($_parenDir=="gas"){
	$pa = "../";
}else{
	$pa="";
}

if($_parenDir=="utenti"){
	$pa = "../";
}else{
	$pa="";
}

$h_menu ='
<div style="padding-bottom:2em;">
<ul class="sf-menu">';  // INIZIO



// ------------------------------------------------Operazioni

 if(($permission & perm::puo_creare_gas)){
			$h_menu .='<li><a class="medium blue awesome" href="#"><b>Crea nuovo GAS</b></a></li>';
 }
// ------------------------------------------------Operazioni


// ------------------------------------------------UTENTI



	  
			$h_menu .='<li><a class="medium green awesome"><b>Visualizza</b></a>'; 
			$h_menu .='<ul>';
		  
				 
					$h_menu .='<li><a class="medium green awesome" href="gas_form.php" target="_self">Il Mio Gas</a></li>'; 
					$h_menu .='<li><a class="medium green awesome" href="gas_table.php" target="_self">Tutti i GAS</a></li>';
					$h_menu .='<li><a class="medium green awesome" href="gas_users.php" target="_self">Utenti Mio Gas</a></li>';

			   
			$h_menu .='</ul>';
			$h_menu .='</li>';

   

// ------------------------------------------------UTENTI


// ------------------------------------------------UTENTI



	  
			$h_menu .='<li><a class="medium magenta awesome"><b>Comunicazioni</b></a>'; 
			$h_menu .='<ul>';
		  
				 
					$h_menu .='<li><a class="medium magenta awesome" href="gas_comunica_gas.php" target="_self">Al mio GAS</a></li>'; 
					$h_menu .='<li><a class="medium magenta awesome" href="gas_comunica_retegas.php" target="_self">a tutta ReteGAS.AP</a></li>';
					if(id_proprio_referente_retegas($gas)==$id_user){
						$h_menu .='<li><a class="medium black awesome" href="gas_comunica_gas_hermes.php" target="_self">Al mio GAS (modalità HERMES)</a></li>'; 
						$h_menu .='<li><a class="medium black awesome" href="gas_comunica_retegas_hermes.php" target="_self">a tutta ReteGAS.AP (modalità HERMES)</a></li>';
							
						
					}
					
			   
			$h_menu .='</ul>';
			$h_menu .='</li>';

   

// ------------------------------------------------UTENTI

 



$h_menu .=''; 
$h_menu .=''; 
$h_menu .='</ul>
		   </div>
		   <br />  
			';                // FINE





$h_table=$h_menu;


?>  