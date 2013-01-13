<?php

include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
	  $menu_aperto=0;
			  //sono io
	  if(user_level($id_user)<5){
		pussa_via();  
		exit;   
	  }

	  if($do=="del"){
		  
		//echo "DO=".$do." ID=".$id;
	  $que = "DELETE FROM retegas_messaggi WHERE retegas_messaggi.id_messaggio='$id' LIMIT 1";	  
	  $result= mysql_query($que);
	  $msg = "Messaggio $id listino cancellato";
		
	  }
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

			
	  // menu      
	  include("amministra_menu_core.php");
	  
	  
	  $query="select * from retegas_messaggi WHERE retegas_messaggi.tipo2='LIS' order by id_messaggio DESC LIMIT 200";
	  $result= mysql_query($query);
	  $numfields = mysql_num_fields($result);

	  $h_table .= "<table>\n<tr>";
	  $h_table .= '<th> OPZ </th>';
	  for ($i=0; $i < $numfields; $i++) 
	  { 
	  $h_table .= '<th>'.mysql_field_name($result, $i).'</th>'; 
	  }
	  $h_table .= "</tr>\n";
   
	  while ($row = mysql_fetch_row($result)) 
	  { 
	  $h_table .= '<tr><td><a href="amministra_messaggi_listini.php?do=del&id='.$row[0].'">Canc</a>
						</td><td>'.implode($row,'</td><td>')."</td></tr>\n"; 
	  }
	  $h_table .= "</table>\n";
	  
		  
	   
	  // END TABELLA ----------------------------------------------------------------------------
	  
	  include ("amministra_main.php");
	  
	  
	  
}else{
	c1_go_away("?q=no_permission");
} 
?>
