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
	  $my_query="SELECT retegas_articoli.*
				FROM retegas_articoli
				WHERE
				id_articoli='$vid_link';";
  
	  $result = $db->sql_query($my_query);   
	  $row = $db->sql_fetchrow($result);
	  
	  
	  $h .= '<span style="text-size=0.7em">Articolo : '.$row["id_articoli"].', codice : '.$row["codice"].'</span>';  
	  $h .= '<h3>'.$row["descrizione_articoli"].'</h3>';
	  $h .= '<div>'.$row["ingombro"].'</div>';
      $h .= '<div>'.$row["articoli_note"].'</div>';
	  
	  echo $h;
	  
	  
	  
	  
	  
  }
  
	

?>
