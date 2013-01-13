<?php
  
  include_once ("../rend.php");
  
  
  if (is_logged_in($user)){
	 $cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		//$gas = id_gas_user($id_user);
		//$gas_name = gas_nome($gas); 
	   
	  
  // QUERY
	  $vid_link = sanitize($vid_link);
	  //echo $vid_link;
	  $my_query="SELECT retegas_cassa_utenti.*
				FROM retegas_cassa_utenti
				WHERE
				id_cassa_utenti='$vid_link';";
  
	  $result = $db->sql_query($my_query);   
	  $row = $db->sql_fetchrow($result);
	  
	  
	  $h .= '<span class="small_link">ID : '.$row["id_cassa_utenti"].'</span>';  
      $h .= '<p>Descr.: '.$row["descrizione_movimento"].'</p>';
      $h .= '<p>Doc : '.$row["numero_documento"].'</p>';
	  $h .= '<p>Note: '.$row["note_movimento"].'</p>';
      
	  
	  echo $h;
  
  }

?>