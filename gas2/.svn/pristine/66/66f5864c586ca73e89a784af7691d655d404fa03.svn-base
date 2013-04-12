<?php
function dareavere_check_ordine($id_ordine,$id_user){
 global $db;
 
 $res = $db->sql_query("SELECT * FROM retegas_dareavere WHERE id_utente='$id_user' AND id_ordine='$id_ordine';");
 
 if($db->sql_numrows($res) >0){
	return "1"; 
 }else{
	return "0";  
 }
}	
?>