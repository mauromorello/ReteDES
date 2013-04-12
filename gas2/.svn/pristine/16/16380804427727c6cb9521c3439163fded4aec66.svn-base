<?php

include_once ("../rend.php");
//include_once ("../Swift-4.0.6/mailer.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
		$menu_aperto=1;
	  
	   // VALORI CELLE CALCOLATE ----------------------      

$h_table .= "
			<div class=\"ui-widget-header ui-corner-all padding-6px m6b\">$titolo_tabella</div> 
			 <table>
			
			<tr>
			<td>";		 
$h_table .=  "<table>
		<tr  class=\"odd\">
			<th $col_1>$h2</th>
			<td $col_2>$c2</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h3</th>
			<td $col_2>$c3</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h4</th>
			<td $col_2>$c4</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h5</th>
			<td $col_2>$c5</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h6</th>
			<td $col_2>$c6</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h7</th>
			<td $col_2>$c7</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h9</th>
			<td $col_2>$c9</td>
		</tr>
		</table>
		</td>
		
		<td>
		<table>
				
		<form method=\"POST\" action=\"utenti_form.php\">
		<tr class=\"odd\">
			<th $col_1>Messaggio:</th>
			<td $col_2><textarea rows=\"5\" name=\"msg_mail\" cols=\"30\"></textarea></td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>&nbsp <input type=\"hidden\" name=\"do\" value=\"send_mail\">
			<input type=\"hidden\" name=\"id\" value=\"$id\"></th>
			<td $col_2><input class=\"large magenta awesome\" type=\"submit\" value=\"Invia\"></td>
		</tr>
		</form>
		</table>
		</td>
		</tr>
		</table>
		<br />
		<div class=\"ui-widget-content ui-corner-all padding_6px\">
		Le informazioni dettagliate degli utenti dei gas diversi dal proprio
		non sono visualizzabili. E' comunque possibile inviare loro un messaggio.
		</div>";

	  // END TABELLA ----------------------------------------------------------------------------
	  
 include ("utenti_main.php");
 
 
}else{
	pussa_via();
	exit;
} 
?>
