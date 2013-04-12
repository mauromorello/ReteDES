<?php

$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

//echo $_parenDir;

if($_parenDir=="ordini_aperti"){
	$pa = "../";
}else{
	$pa="";
}


include_once ($pa."../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
	  
	  // MENU APERTO
	  $menu_aperto=1;
		
include ($pa."../articoli/articoli_form_core.php");


	  
include ($pa."../articoli/articoli_main.php");
 
 
}else{
	pussa_via();
} 
?>
