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
        $opz = leggi_opzioni_sito_utente($id_user);
		 
		
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

include("oc_g_art_pdf_header.php");
// $h = header
// $style = foglio di stile


ob_start(); // Start buffer 

if (($opz & opti::stampe_senza_intestazioni)){unset($h);}

$h_table = "<html>
			<head>
			$style			
			</head>			
			<body>
			<div style=\"margin:30px;\">$h";

if (!($opz & opti::stampe_senza_intestazioni)){            
    include("../ordini_chiusi_form_scheda_fornitore.php");
}
    //$id=$c2;

include ("oc_g_art.php");


$h_table.="</div>
			</body>
				</html>";

$script = '<script type="text/php">
if ( isset($pdf) ) {
$font = Font_Metrics::get_font("verdana", "bold");

$w=$pdf->get_width();
$h=$pdf->get_height();
$y=$h-2*$text_height-30;
$pdf->line(16,$y,$w-16,$y,$color,1);
$pdf->page_text(560, $y+3 , "Pagina: {PAGE_NUM} of {PAGE_COUNT}", $font,6, array(0,0,0));
}
</script>';
$h_table = str_replace('<body>', '<body>'.$script, $h_table);			
				
				
				
echo $h_table;

$pdf_html = ob_get_contents(); // Get the contents of file into variable for later use  
ob_end_clean(); // Close buffers 

if($is_pdf=="screen"){
	echo $pdf_html;     
}else{
	require_once("../../lib/dompdf/dompdf_config.inc.php");
	$dompdf = new DOMPDF();
	$dompdf->set_base_path("../../../gas2/images/");
	$dompdf->load_html($pdf_html);
	
	$dompdf->render();
	srand((double)microtime()*1000000);  
	$n_random= rand(0,100000);
	
	$dompdf->stream("Ordine_".$id,array("Attachment" => 1));
	//Ordine_".$id."_mia_spesa_".$n_random.".pdf
}
//echo $pdf_html;

}else{
	pussa_via();
	
}
?>