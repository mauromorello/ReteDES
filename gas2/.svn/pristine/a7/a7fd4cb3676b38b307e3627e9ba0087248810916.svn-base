<?php
if (eregi("ordini_aperti_main.php", $_SERVER['SCRIPT_NAME'])) {
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
	  
	  if (!empty($has_fg)){
		 $h .= java_head_fg_menu();
		 $h .= fg_css();  
	  }
	  
	  if (!empty($id_help)){
		$h .= java_accordion("#$id_help",false);
	  }
	  
	  $h .= java_head_jquery_superfish();
	  
	  
	  
	  if (!empty($table_group_name)){		
		$h .= java_head_jquery_tablegroup();
	  }
	  if (!empty($table_sorter_name)){
		$h .= java_head_jquery_tablesorter();
	  }
	  if (!empty($qtip_on)){
		$h .= java_head_jquery_qtip();
	  }
	  if (!empty($sparkline_on)){
		$h .= java_head_jquery_sparkline();
	  }
	  if (!empty($progression)){
		$h .= java_head_jquery_metadata();  
		$h .= java_head_jquery_progression();
	  }
	  $h .= java_head_datetimepicker();
	  
	  
	  if (!empty($cke)){
		  $h .= java_head_ckeditor();
	  }
	  
	  //$h .= java
	  $h .= "</HEAD> <! CLOSE HEADER !>\n"; 
	  // HEADER HTML
	  
	  
	  // BODY
	  $h .= '<body>'; 
	  if (!empty($table_sorter_name)){
		  $h .= java_tablesorter($table_sorter_name);
	  }
	  if (!empty($table_group_name)){
		  $h .= java_tablegroup($table_group_name);
	  }
	  $h .= java_superfish();
	  
	   
	  $h .= c1_open_div("container");             // CONTAINER
	  $h    .= c1_open_div("header");             // HEADER
	  $h        .=c1_header_page($username,$gas_name,$menu,$posizione);
	  $h    .= c1_close_div();                    //HEADER
	  $h    .= c1_open_div("navigation");         //NAVIGATION
	  
	  if (is_logged_in($user))
			{
	  $h        .= c1_navigation_2();
			}else{
	  $h        .= c1_navigation_1();          
			}
	  
	   
	  $h    .= c1_close_div();                    //NAVIGATION
	  
	  
	  $h    .= java_dialog_msg($msg);            // DIV MSG
	  
	  $h    .= c1_open_div("content");  // CONTENT 
		$h    .= $h_table;              // RENDER TABELLA	 
	  $h    .= c1_close_div();          // CONTENT
	  $h    .= c1_close_div();
	  
	  if (!empty($datetimepicker1)){$h .= c1_ext_javascript_datetimepicker($datetimepicker1);}
	  if (!empty($datetimepicker2)){$h .= c1_ext_javascript_datetimepicker($datetimepicker2);}
	 
	  if (!empty($qtip_on)){ 
		$h    .= java_qtip();
	  }               // TOOLTIPS
	  if (!empty($qtip_ajax)){
		$h    .= java_qtip_ajax($qtip_ajax);  
	  }
	  if (!empty($sparkline_on)){ 
		$h    .= java_sparkline($range_max);
		$h    .= java_sparkline_pie($sparkline_pie_reference);
	  }
	  if (!empty($progression)){
		$h .= java_progression(".progressbar");
	  }
	  $h    .= '
				</body>
				</html>';
	  
	  
	  
	   
	   $conversion_chars = array (    "à" => "&agrave", 
							   "è" => "&egrave", 
							   "é" => "&egrave", 
							   "ì" => "&igrave", 
							   "ò" => "&ograve", 
							   "ù" => "&ugrave"); 

$h= str_replace (array_keys ($conversion_chars), array_values 
($conversion_chars), $h);
	   //echo phpinfo();
	   echo $h;
	   
	   // Specify configuration
	   //$config = array(
	//	   'indent'         => true,
	//	   'output-xhtml'   => true,
	//	   'wrap'           => 200);

	  // $tidy = new tidy();
	  // $tidy->parseString($h, $config, 'utf8');
	  // $tidy->cleanRepair();
	   
	//   echo $tidy;
	   
}else{
	pussa_via();
} 
?>