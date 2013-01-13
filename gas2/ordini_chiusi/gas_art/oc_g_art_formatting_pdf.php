<?php
  if (eregi("oc_g_formatting_pdf.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}

	  

  //  LARGHEZZA E CLASSI COLONNE

	  $col_1="width=\"10%\" class=\"gas_c1\" style=\"text-align:left;\"";
	  $col_2="width=\"25%\" class=\"gas_c1\" style=\"text-align:left;\"";
	  $col_3="width=\"7%\" class=\"gas_c1\" style=\"text-align:center;\"";
	  $col_4="width=\"7%\" class=\"gas_c1\" style=\"text-align:center;\"";
	  $col_5="width=\"7%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_6="width=\"7%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_7="width=\"7%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_8="width=\"7%\" class=\"gas_c1\" style=\"text-align:right;\""; 
	  $col_9="width=\"7%\" class=\"gas_c1\" style=\"text-align:right;\""; 
	  
	  $euro = "";
	  $nbsp = "";
?> 