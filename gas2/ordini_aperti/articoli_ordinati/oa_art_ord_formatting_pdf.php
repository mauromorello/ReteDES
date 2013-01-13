<?php
  if (eregi("oa_art_ord_formatting_pdf.php", $_SERVER['SCRIPT_NAME'])) {
	 Header("Location: ../../index.php"); die();
}

	  // INTESTAZIONI
	  
	  $h1="";      
	  $h2="Codice";
	  $h3="Descrizione";      
	  $h4="Prezzo singolo";
	  $h5="Richieste articolo";
	  $h6="Quantità<br>TOTALE";
	  $h7="Totale";
	  $h8="Scatole intere";
	  $h9="AVANZO";
	   
	  $h10="Costi"; 
	  $h11="Totale";

  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"0px\"";    //opzioni    
	  $col_2="width=\"10%\" class=\"gas_c1\" style=\"text-align:left;\"";
	  $col_3="width=\"30%\" class=\"gas_c1\" style=\"text-align:left;\"";
	  $col_4="width=\"10%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_5="width=\"6%\" class=\"gas_c1\" style=\"text-align:center;\"";
	  $col_6="width=\"6%\" class=\"gas_c1\" style=\"text-align:center;\"";
	  $col_7="width=\"6%\" class=\"gas_c1\" style=\"text-align:right;\"";
	  $col_8="width=\"6%\" class=\"gas_c1\" style=\"text-align:center;\"";
	  $col_9="width=\"6%\" class=\"gas_c1\" style=\"text-align:center;\"";

	  
	  $euro = "Eu.";
	  $nbsp = "";
?>