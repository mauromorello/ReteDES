<?php
include_once ("../../rend.php");   
if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);

include("../ordini_chiusi_sql_core.php");
include("oc_art_ord_pdf_header.php");
// $h = header
// $style = foglio di stile
//echo "A QUESTO PUNTO ID = ".$id;

ob_start(); // Start buffer 

$h_table = "<html>
			<head>
			$style			
			</head>			
			<body>
			<div style=\"margin:30px;\">$h<br>";

include("../ordini_chiusi_form_scheda.php");
//$id=$c2;

include ("oc_art_ord.php");


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
	$dompdf->set_base_path("../../../images/");
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
