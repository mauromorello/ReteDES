<?php
include_once ("../../rend.php");   
require_once("../../lib/dompdf/dompdf_config.inc.php");

$id=$_POST["id"];
$id_user=2;



$h_table="<html><body>";
include("../ordini_chiusi_table.php");
$h_table.="</body></html>";




$dompdf = new DOMPDF();
$dompdf->load_html($h_table);
$dompdf->render();
$dompdf->stream("sample.pdf");


?>
