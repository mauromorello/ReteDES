<?php

  if (eregi("oc_mia_spesa_pdf_header.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}

if($is_pdf=="screen"){
$img_path = "../../../images/rg.jpg";
}else{
$img_path = "rg.jpg";  	    
}


  $h = "
<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td width=\"50%\" align=\"left\">
		<a href=\"http://morello.ca\">
		<img align=\"left\" src=\"$img_path\" border=\"0\" width=\"300\" height=\"75\" alt=\"ReteGas.AP\">
		</a>
	</td>
	<td style=\"padding-right:10px; text-align:right\">";
//if(!empty($usr)){
$h.="
					Rete dei GAS dell'Alto Piemonte<br />
					<span class=\"small_link \">da un'idea ed un progetto
					del GAS di Borgomanero</span><br /> 
					<span class=\"small_link\">retegas.ap@gmail.com</span><br />
					<span class=\"small_link \">Sviluppo :</span>ma.morez@tiscali.it<br />
					";//<span class=\"\">LogOut</span>
					//";
//}                        
$h.="</td>
	</tr>
</table>
";      
	  
	  
$style = "
<style type=\"text/css\">

html {
font-family:arial,helvetica;
}

table{
	width:100%;
	font-size:10px;    
}

table th{
 background-color:#cacaca;
}

.titolino td{
	background-color :#ACACAC;
	font-size: 12px;
	text-align: center;
	font-weight: bold;
	padding: 4px;
}

table .mia_spesa td{
border-bottom: 1px solid #444;
}

table .odd td{
background-color :#CFCFCF; 
}

table .costo1 td{
background-color :#F0D362; 
}

table .costo2 td{
background-color :#F0D362; 
}

table .subtotal td{
	background:#C0FFC0;
	border-bottom : 3px solid #000;
	font-size: 12px;
	font-weight: bold;
}

</style>";


//$page = "";


  
?>
