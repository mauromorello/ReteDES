<?php

if (eregi("articoli_sql.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

	  // Nometabella
	  
	  $tn = "retegas_articoli";
		
	  // SQL NOMI DEI CAMPI
	  $d1="id_ordini";
	  $d2="id_listini";
	  $d3="id_utente";
	  $d4="descrizione_ordini";
	  $d5="data_scadenza1";
	  $d6="data_scadenza2";
	  $d7="data_apertura";
	  $d8="data_chiusura";
	  $d9="data_merce";
	  $d10="costo_trasporto";
	  $d11="costo_gestione";
	  $d12="chiuso_ordini";
	  $d13="privato";
	  $d14="min_articoli";
	  $d15="min_scatola";
	  $d16="id_stato";
	  $d17="senza_prezzo";
	  
	   // INTESTAZIONI CAMPI
	  $h1="ID";
	  $h2="ID Listino";
	  $h3="ID Utente";
	  $h4="Descrizione";
	  $h5="data_scadenza1";
	  $h6="data_scadenza2";
	  $h7="Apre il";
	  $h8="Chiude il";
	  $h9="data_merce";
	  $h10="Costo Trasporto";
	  $h11="Costo Gestione";
	  $h12="chiuso_ordini";
	  $h13="Privato";
	  $h14="Minimo Articoli";
	  $h15="Minimo Scatole";
	  $h16="Stato";
	  $h17="Senza prezzo";
?>