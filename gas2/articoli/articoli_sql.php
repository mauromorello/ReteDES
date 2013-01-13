<?php

if (eregi("articoli_sql.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

	  // Nometabella
	  
	  $tn = "retegas_articoli";
		
	  // SQL NOMI DEI CAMPI
	  $d1="id_articoli";
	  $d2="id_listini";
	  $d3="codice";
	  $d4="u_misura";
	  $d5="misura";
	  $d6="descrizione_articoli";
	  $d7="qta_scatola";
	  $d8="prezzo";
	  $d9="ingombro";
	  $d10="qta_minima";
	  $d11="qta_multiplo";
	  $d12="articoli_note";
	  $d13="articoli_unico";
	   // INTESTAZIONI CAMPI
	  $h1="ID";
	  $h2="Listino";
	  $h3="Codice";
	  $h4="Unità di misura";
	  $h5="Misura";
	  $h6="Descrizione";
	  $h7="Quantità scatola";
	  $h8="Prezzo";
	  $h9="Ingombro";
	  $h10="Quantità multiplo";
	  $h11="Quantità multiplo";
	  $h12="Note";
	  $h13="Articolo Univoco";
?>
