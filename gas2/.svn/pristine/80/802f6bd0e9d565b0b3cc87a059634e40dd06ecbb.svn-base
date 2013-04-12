<?php

include_once ("../rend.php");


if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
	  $menu_aperto=menu_lat::user;
			  //sono io
			  
	  $my_user_level = user_level($id_user);
			  
	  if($my_user_level<1){
		pussa_via();  
		exit;   
	  }
	  
	  
	   // menu      
	  include("amministra_menu_core.php");
	  
	  
	  //OPERAZIONI

	  
	  
	 $h_table= phpinfo(); 
	  
	  

		  
	   
	  // END TABELLA ----------------------------------------------------------------------------
	  
	  include ("amministra_main.php");
	  
	  
	  
}else{
	c1_go_away("?q=no_permission");
} 
?>
