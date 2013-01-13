<?php
include_once ("../../rend.php");
include_once ("../../ordini/ordini_renderer.php");
   
if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);

		 
		
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

include("oc_lis_art_pdf_header.php");
// $h = header
// $style = foglio di stile


ob_start(); // Start buffer 

$h_table = "<html>
			<head>
			$style			
			</head>			
			<body>
			<div style=\"margin:30px;\">$h<br>";

include("../ordini_chiusi_form_scheda_fornitore.php");
//$id=$c2;

include ("oc_lis_art.php");



$h_table.="</div>
			</body>
				</html>";
		
				
				
				
echo $h_table;

$pdf_html = ob_get_contents(); // Get the contents of file into variable for later use  
ob_end_clean(); // Close buffers 

if($is_pdf=="screen"){
	echo $pdf_html;     
}else{
	echo "Questa pagina non è possibile averla in pdf"; 
}
//echo $pdf_html;

}else{
	pussa_via();
	
}
?>
