<?php
  if (eregi("oc_art_ord_si_formatting_pdf.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}

	  // INTESTAZIONI


  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"15%\" class=\"gas_c1\" style=\"text-align:left;\"";    //opzioni    
	  $col_2="width=\"35%\" class=\"gas_c1\" style=\"text-align:left;\"";
	  $col_3="width=\"10\" class=\"gas_c1\"  style=\"text-align:right;\"";
	  $col_4="width=\"10%\" class=\"gas_c1\" style=\"text-align:center;\"";
	  $col_5="width=\"10%\" class=\"gas_c1\" style=\"text-align:center;\"";
	  $col_6="width=\"10%\" class=\"gas_c1\" style=\"text-align:right;\"";

	  
	  $euro = "Eu.";
	  $nbsp = "";
?>
