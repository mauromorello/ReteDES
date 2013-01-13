<?php
  
require_once("../../lib/dompdf/dompdf_config.inc.php");
$id=$_POST["id"];
$id_user=2;
include("../ordini_chiusi_form_scheda.php");
//$h =
//  '<html><body>'.
//  '<p>Put your html here, or generate it with your favourite '.
//  'templating system.</p>'.
//  '</body></html>';

$dompdf = new DOMPDF();
$dompdf->load_html("PROT");
$dompdf->render();
$dompdf->stream("sample.pdf");


?>
