<?php
if (eregi("ordini_chiusi_main.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

//include_once ("../rend.php");

if (is_logged_in($user)){
	  
	  // HEADER HTML
	  $h  = c1_header();
	  $h .= c1_ext_stylesheets();
	  $h .= c1_ext_javascript();
	  $h .= c1_ext_javascript_progressbar();      
	  
	  
	  
	  
	  // tablesorter
	  $h .= "<script type=\"text/javascript\" src=\"../js/jquery.tablesorter.min.js\"></script> ";
	  
	  $h .= c1_javascript($menu_aperto);

	  $h .= c1_close_header();
	  $h .= '<body>';
	  
	  $h .= '
			<script type="text/javascript">				
			$(document).ready(function() 
					 { 
					 $("#ordini").tablesorter({widgets: [\'zebra\'],
											   cancelSelection : true,
											   dateFormat: \'uk\'}); 
			} 
			);
			</script>';
	  
	  $h .= c1_open_div("container");
	  $h    .= c1_open_div("header");
	  $h        .=c1_header_page($username,$gas_name,$menu,$posizione);
	  $h    .= c1_close_div();
	  $h    .= c1_open_div("navigation");
	  
	  if (is_logged_in($user))
			{
	  $h        .= c1_navigation_2($id_user);
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
	  
	  if (!empty($datetimepicker1)){$h .= c1_ext_javascript_datetimepicker($datetimepicker1);}
	  if (!empty($datetimepicker2)){$h .= c1_ext_javascript_datetimepicker($datetimepicker2);} 
	  
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
