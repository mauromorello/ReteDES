<?php
if (eregi("gas_main.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

//include_once ("../rend.php");

if (is_logged_in($user)){
	  
	 
	
	
	  // HEADER HTML
	  $h  = c1_header();
	  $h .= c1_ext_stylesheets();
	  $h .= java_head_jquery();
	  $h .= java_head_jquery_ui_1_8_5();
	  if(!empty($msg)){
		$h .= java_dialog("",$msg);
	  }
	  $h .= java_head_jquery_accordion(); 
	  $h .= java_accordion("#accordion",$menu_aperto);
	  $h .= java_head_jquery_superfish();
	  if (!empty($table_sorter_name)){
		$h .= java_head_jquery_tablesorter();
	  }  
	  $h .= "</HEAD> <! CLOSE HEADER !>\n"; 
	  // HEADER HTML
	  
	  
	  // BODY
	  $h .= '<body>'; 
	  if (!empty($table_sorter_name)){
		  $h .= java_tablesorter($table_sorter_name);
	  } 
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
	pussa_via();
} 
?>
