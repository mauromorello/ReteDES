<?php

if (eregi("ordini_chiusi_sql.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

	  // Nometabella
	  
	  $tn = "retegas_articoli";
		
	  // SQL NOMI DEI CAMPI
	  $d1="id_ordini";
	  $d2="descrizione_ordini";
	  $d3="descrizione_listini";
	  $d4="descrizione_ditte";
	  $d5="data_chiusura";
	  $d6="descrizione_gas";
	  $d7="id_gas_referenze";
	  $d8="userid";
	  $d9="fullname";
	  $d10="id_utente";
		
	  
	  
	   // INTESTAZIONI CAMPI
	  $h1="id_articoli";
	  $h2="Descrizione";
	  $h3="Listino";
	  $h4="Ditta";
	  $h5="Chiuso il";
	  $h6="GAS";
	  $h7="id_gas_referenze";
	  $h8="Referente";
	  $h9="Referente";
	  $h10="id_utente";
	  $h11="ID/Stato";
	  $h12="Totale lordo";
	  $h13="Mia spesa";
?>
