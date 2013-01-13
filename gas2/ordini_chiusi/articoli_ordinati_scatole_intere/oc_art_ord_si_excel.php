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
		
		if($is_pdf=='excel'){
			$filename = "Ordine_$id-SOLO_SCATOLE_INTERE.xls";
			header ("Content-Type: application/vnd.ms-excel");
			header ("Content-Disposition: inline; filename=$filename");

		}else{
		if($is_pdf=='word'){
			$filename = "Ordine_$id-SOLO_SCATOLE_INTERE.doc";
				header("Content-Type: application/msword");
				header ("Content-Disposition: attachment; filename=$filename");
				}else{
					exit;
				}

		}

if($is_pdf=="excel"){$is_excel="TRUE";}               

unset($is_pdf);
   
include ("oc_art_ord_si.php");

echo $h_table;

   
}else{
	pussa_via();
	
}
?>
