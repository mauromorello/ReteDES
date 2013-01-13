<?php

include_once ("../rend.php");
global $db;

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
	  $menu_aperto=menu_lat::user;
	  $delete_command="#";
			  //sono io
	  $my_user_level=user_level($id_user);	  
	   
			  
	  if($my_user_level<1){
		pussa_via();  
		exit;   
	  }
	  
	  $query="SELECT
				maaking_users.userid,
				maaking_users.fullname,
				maaking_users.username,
				maaking_users.tel,
				maaking_users.email,
				maaking_users.user_level,
				maaking_users.lastlogin,
				maaking_users.last_activity,
				retegas_gas.descrizione_gas
				FROM
				maaking_users
				Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas";
	  
	  switch ($do){
		case "all":
			$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">TUTTI GLI UTENTI DI RETEGAS</div><br></hr>';
			$query=$query." ORDER BY userid DESC";
		break;
		
		case "not_act":
			$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">UTENTI NON ANCORA ATTIVATI</div><br></hr>';
			$query=$query." WHERE isactive<>'1';";
		break;
		
		case "last_act":
			$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">Ultime attivit?</div><br></hr>';
			$query =$query." ORDER BY last_activity DESC LIMIT 10";
		break;
	   
	   default:
			$query="";
			pussa_via();
			exit;
	   break;
		
	  }
	  
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

			
	  // menu      
	  include("amministra_menu_core.php");
	  
	  for($s=0;$s<1000;$s++){
	  $colo[$s]=random_color();	      
	  }
	  
	  
	  
	  $result= mysql_query($query);
	  $numfields = mysql_num_fields($result);
	  $h_table .="</ hr>";
	  $h_table .= $intestazione;
	  $h_table .= "<table>\n<tr>";
	  //$h_table .= '<th> OPZ </th>';
	  $h_table .="<th>ID</th>";
	  $h_table .="<th>NOME</th>"; 
	  $h_table .="<th>U_NAME</th>"; 
	  $h_table .="<th>TEL</th>"; 
	  $h_table .="<th>MAIL</th>"; 
	  $h_table .="<th>LEV.</th>"; 
	  $h_table .="<th>L_LI</th>"; 
	  $h_table .="<th>L_ACT</th>"; 
	  $h_table .="<th>GAS</th>"; 
	  
	  $h_table .= "</tr>\n";
   
	 
   
	  while ($row = mysql_fetch_row($result)) 
	  {
		  
		 
	  $h_table.= '<tr style="background-image:-webkit-gradient(linear,0 0,0 50,from(#E0E0E0),to(#FFFFFF));">
						<td style="font-weight:bold;vertical-align:middle;">
						<a href="'.$RG_addr["amministra_user_info"].'?id='.$row[0].'"> 
                        '.$row[0].'
						</td>
						<td style="background-color:#'.$colo[$row[1]].';display: inline-block; vertical-align:middle">
						<a href="'.$RG_addr["user_permission_site"].'?id='.mimmo_encode($row[0]).'">
						'.$row[1].'
						</a>
						</td>
						<td style="background-color:#'.$colo[$row[2]].';display: inline-block; vertical-align:middle">
						'.$row[2].'
						</td>
						<td>
							'.$row[3].'
						</td>
						<td>
							'.$row[4].'
						</td>
						<td>
							'.$row[5].'
						</td>
						<td>
							'.conv_datetime_from_db($row[6]).'
						</td>
						<td>
							'.conv_datetime_from_db($row[7]).'
						</td>
						<td>
							'.$row[8].'
						</td>

	  
					</tr>';
	  
	   
	  //$h_table .= '<tr><td></td><td  style="background-color:#'.$colo["$row[2]"].';display: block;">'.implode($row,'</td><td>')."</td></tr>\n"; 
	  }
	  $h_table .= "</table>\n";
  
	   
	  // END TABELLA ----------------------------------------------------------------------------
	  
	  include ("amministra_main.php");
	  
	  
	  
}else{
	c1_go_away("?q=no_permission");
} 
?>
