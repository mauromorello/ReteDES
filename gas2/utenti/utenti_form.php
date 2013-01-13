<?php

include_once ("../rend.php");
include_once ("utenti_render.php");

//include_once ("../Swift-4.0.6/mailer.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$permission = $cookie_read[6]; 
		
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
if($do=="change_user_permissions"){
	  //controllo che sia un utente del mio gas
			if(id_gas_user($id_utente_permessi)<>$gas){
				$msg="Puoi cambiare i permessi solo agli utenti del tuo gas";
				unset($do);
				include ("utenti_form.php");
				exit;
			}
			
			//controllo di avere i privilegi
			if($permission & perm::puo_mod_perm_user_gas){
			
				//echo "PCO : ".$p_c_o."<br>";
				//echo "PPO : ".$p_p_o."<br>";
				//echo "PCG : ".$p_c_g."<br>";
				//echo "PCD : ".$p_c_d."<br>";
				//echo "PCL : ".$p_c_l."<br>";
				
				
				$UP =   (int)$p_c_o | 
                        (int)$p_p_o | 
                        (int)$p_c_g | 
                        (int)$p_c_d | 
                        (int)$p_c_l | 
                        (int)$p_m_p | 
                        (int)$p_a_a | 
                        (int)$p_s_b |
                        (int)$p_e_m |
                        (int)$i_n_u |
                        (int)$p_v_t_o|
                        (int)$p_o_c ; 
				
				
				//echo "UP : ".$UP."<br>";
			  
				$db->sql_query("UPDATE maaking_users SET user_permission = '$UP' WHERE userid='$id_utente_permessi'");
	
				$msg="Permessi modificati";
				unset($do);
				$id=$id_utente_permessi;
				
				include ("utenti_form.php");
				exit;
				
			}
	  
	  
	  //cambio i permessi;	  
		  
		  
	  }
	  
if($do=="change_user_permissions_zeus"){
	  
		  
		  //controllo di essere zeus
			if(user_level($id_user)<>5){
				$msg="Mica sei ZEUS !!";
				unset($do);
				include ("utenti_form.php");
				exit;
			}
			
			//controllo di avere i privilegi
			
				//echo "PCO : ".$p_c_o."<br>";
				//echo "PPO : ".$p_p_o."<br>";
				//echo "PCG : ".$p_c_g."<br>";
				//echo "PCD : ".$p_c_d."<br>";
				//echo "PCL : ".$p_c_l."<br>";
				
				
				$UP =   (int)$p_c_o | 
						(int)$p_p_o | 
						(int)$p_c_g | 
						(int)$p_c_d | 
						(int)$p_c_l | 
						(int)$p_m_p | 
						(int)$p_a_a | 
						(int)$p_s_b | 
						(int)$p_e_m |
						(int)$i_n_u |
                        (int)$p_v_t_o|
                        (int)$p_o_c;   
				
				//echo "UP : ".$UP."<br>";
				
				$db->sql_query("UPDATE maaking_users SET user_permission = '$UP' WHERE userid='$id_utente_permessi'");
	
				$msg="Permessi modificati da ZEUS";
				unset($do);
				$id=$id_utente_permessi;
				
				include ("utenti_form.php");
				exit;
				
			
	  
	  
	  //cambio i permessi;      
		  
		  
	  }
	  
	  
	  
	  if($do=="send_mail"){
		  
		  $da_chi = fullname_from_id($id_user);
		  $mail_da_chi = id_user_mail($id_user);
		
		  $verso_chi = fullname_from_id($id); 
		  $mail_verso_chi = id_user_mail($id);
			
		  $soggetto = "[RETEGAS AP] - da $da_chi - Comunicazione";
		  manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$msg_mail);
			
		  $msg="Mail inviata a $verso_chi";
		  unset($do);
		  include ("utenti_form.php");
		  exit;
	  }
	  
	  // MENU APERTO
	  $menu_aperto=2;
		
	  // QUERY
	  (int)$id;
	  $my_query="SELECT * FROM maaking_users WHERE  (userid='$id') LIMIT 1";
	  
	  // SQL NOMI DEI CAMPI
	  $d1="userid";
	  $d2="fullname";
	  $d3="indirizzo";
	  $d4="country";
	  $d5="city";
	  $d6="mail";
	  $d7="tel";
	  $d8="profile";
	  $d9="id_gas";
	  
	  // TITOLO TABELLA
	  $titolo_tabella="Scheda personale utente";
	  
	  // INTESTAZIONI CAMPI
	  $h1="ID";
	  $h2="Nome";
	  $h3="";
	  $h4="Indirizzo";
	  $h5="Città";
	  $h6="mail";      
	  $h7="telefono";
	  $h8="note";
	  $h9="GAS";
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="";
	  $col_2=""; 


	  
	  
	  
	  // OPZIONI
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = mysql_query($my_query);
	  $row = mysql_fetch_array($result);  
	  
	  //$h_table .= ditte_menu_2($id,$id_user);
	  

		 
		 // VALORI DELLE CELLE da DB---------------------
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = $row["$d3"];
			  $c4 = $row["$d4"];
			  $c5 = $row["$d5"];
			  $c6 = $row["$d6"];
			  $c7 = $row["$d7"];
			  $c8 = $row["$d8"];
			  $c9 = $row["$d9"];
			  
              $user_permission = $row["user_permission"];
			  
	   if (($c9<>$gas) & (user_level($id_user)<>5)){
		  $c3 =  $c4 = $c5 = $c6 = $c7 = $c8 = "Non disponibile"; 
		   
	   }
	   $c9 = gas_nome($c9);       
			 // VALORI CELLE CALCOLATE ----------------------      

$h_table .= "<div class=\"ui-widget-header ui-corner-all padding-6px m6b\">
			<div style=\"margin-bottom:16px;\">$titolo_tabella</div> 
			 <table>
			
			<tr>
			<td>";		 
$h_table .=  "<table>
		<tr  class=\"odd\">
			<th $col_1>$h2</th>
			<td $col_2>$c2</td>
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
		<tr>
			<th $col_1>&nbsp <input type=\"hidden\" name=\"do\" value=\"send_mail\">
			<input type=\"hidden\" name=\"id\" value=\"$id\"></th>
			<td $col_2><input class=\"large magenta awesome\" type=\"submit\" value=\"Invia\"></td>
		</tr>
		</form>
		</table>
		</td>
		</tr>
		</table>
		</div>
		<br />
		
		<div class=\"ui-widget-content ui-corner-all padding_6px mb6\" style=\"margin-bottom:16px;\">
		Le informazioni dettagliate degli utenti dei gas diversi dal proprio
		non sono visualizzabili. E' comunque possibile inviare loro un messaggio.
		</div>";

	  // END TABELLA ----------------------------------------------------------------------------
 

 // SE SONO ZEUS posso modificare i permessi degli altri utenti
	if(user_level($id_user)==5){
		include("utenti_form_permissions_zeus.php");
	}else{
		
			
		 // SE SONO UN USER CON PERMESSI PER FARLO APRO LA TABELLA MODIFICA PERMESSI
         if(id_gas_user($id)==$gas){
			if((int)$permission & (int)perm::puo_mod_perm_user_gas){
				include("utenti_form_permissions.php");
			}
         }    
}
 


	  
 include ("utenti_main.php");
 
 
}else{
	pussa_via();
	exit;
} 
?>