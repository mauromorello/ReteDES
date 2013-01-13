<?php

if (eregi("ditte_main.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

include_once ("../rend.php");

if (is_logged_in($user)){
	  
	  // HEADER HTML
	  $h  = c1_header();
	  $h .= c1_ext_stylesheets();
	  $h .= c1_ext_javascript();
	  //$h .= c1_ext_javascript_tooltip($tt_5_id,$tt_5_class);
	  //$h .= c1_ext_javascript_tooltip($tt_6_id,$tt_6_class);
	  $h .= c1_javascript($menu_aperto);
	  $h .= c1_close_header();
	  $h .= c1_open_body(); 
	  $h .= c1_open_div("container");
	  $h    .= c1_open_div("header");
	  $h        .=c1_header_page($username,$gas_name,$menu,$posizione);
	  $h    .= c1_close_div();
	  $h    .= c1_open_div("navigation");
	  
	  if (is_logged_in($user))
			{
	  $h        .= c1_navigation_2();
			}else{
	  $h        .= c1_navigation_1();          
			}
	  
	   
	  $h    .= c1_close_div();
	  if(!empty($msg)){
	  $h    .= "<div id=\"dialog-message\" title=\"ReteGas A.P.\">
		<p>
			<span class=\"ui-icon ui-icon-circle-check\" style=\"float:left; margin:0 7px 50px 0;\"></span>
			$msg
		</p>

	</div>";
	  }
	  $h    .= c1_open_div("content");  
	  $h    .= $h_table;                    // RENDER TABELLA	 
	
	  $h .= footer_user();
	   
	   $conversion_chars = array (    "à" => "&agrave", 
							   "è" => "&egrave", 
							   "é" => "&egrave", 
							   "ì" => "&igrave", 
							   "ò" => "&ograve", 
							   "ù" => "&ugrave"); 

$h= str_replace (array_keys ($conversion_chars), array_values 
($conversion_chars), $h);
	   echo $h;
}else{
	c1_go_away("?q=no_permission");
} 
?>
