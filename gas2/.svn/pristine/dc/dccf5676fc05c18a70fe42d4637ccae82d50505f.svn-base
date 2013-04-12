<?php

include_once ("../rend.php");
include_once ("amministra_renderer.php");
//

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
	  
	  $menu_aperto=menu_lat::user;
			  //sono io
			  
	  $my_user_level = user_level($id_user);
			  
	  if(!(leggi_permessi_utente($id_user) & perm::puo_gestire_retegas)){
		pussa_via();  
		exit;   
	  }
	  
	  
	   // menu      
	  include("amministra_menu_core.php");
	  
	  
	  //OPERAZIONI
	  
      
	  if($do=="m_ON"){write_option_text(0,"MAILER","ON");};
	  if($do=="m_OFF"){write_option_text(0,"MAILER","OFF");};
	  if($do=="geocode"){$last_geocoding = geocode_users_table("SELECT * FROM maaking_users WHERE (user_gc_lat = 0);"); }
	  if($do=="d_ON"){write_option_text(0,"DEBUG","ON");};
	  if($do=="d_OFF"){write_option_text(0,"DEBUG","OFF");};
	  
	  
	  
	  // USER NON ATTIVI
	  
	  $u_n_a = user_non_attivi();
	  if($u_n_a==0){		  
		$user_non_attivi='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">Tutti gli users sono attivi</div>';    
	  }else{
		$user_non_attivi='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">Ci sono <b>'.$u_n_a.'</b> users non ancora attivi</div>';      
	  }
	  
	  
	  // Dettagli ordini senza ordine
	  
	  $d_o_s_o = db_dettagli_ordine_senza_ordine();
	  if($d_o_s_o==0){          
		$dettagli_ordini_senza_ordine='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">DETTAGLI ORDINE -> ORDINE: <b>OK</b></div>';    
	  }else{
		$dettagli_ordini_senza_ordine='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">DETTAGLI ORDINE -> ORDINE : Ci sono <b>'.$d_o_s_o.'</b> dettagli ordine senza ordine papà. </div>';      
	  }
	  
	  // Distribuzione senza dettagli
	  $d_s_s_d_o = db_distribuzione_spesa_senza_dettagli_ordine();
	  if($d_s_s_d_o==0){          
		$distribuzione_spesa_senza_dettagli_ordine='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">DISTRIBUZIONE SPESA -> DETTAGLI ORDINE : <b>OK</b></div>';    
	  }else{
		$distribuzione_spesa_senza_dettagli_ordine='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">DISTRIBUZIONE SPESA -> DETTAGLI ORDINE : Ci sono <b>'.$d_s_s_d_o.'</b> distribuzioni spesa SENZA dettagli ordine.</div>';      
	  } 
	  
	  // Amici senza referente
	  $a_s_r = db_amici_senza_referente();
	  if($a_s_r==0){          
		$amici_senza_referente='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">AMICI -> USER : <b>OK</b></div>';    
	  }else{
		$amici_senza_referente='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">AMICI -> USER : Ci sono <b>'.$a_s_r.'</b> amici SENZA referente.</div>';      
	  }
	  
	  // Articoli senza listino
	  $a_s_l = db_articoli_senza_listino();
	  if($a_s_l==0){          
		$articoli_senza_listino='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">ARTICOLI -> LISTINI : <b>OK</b></div>';    
	  }else{
		$articoli_senza_listino='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">ARTICOLI -> LISTINI : Ci sono <b>'.$a_s_l.'</b> Articoli SENZA Listino.</div>';      
	  }
	  
	  // Listini senza ditte
	  $l_s_d = db_listini_senza_ditte();
	  if($l_s_d==0){          
		$listini_senza_ditte='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">LISTINI -> DITTE : <b>OK</b></div>';    
	  }else{
		$listini_senza_ditte='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">LISTINI -> DITTE : Ci sono <b>'.$a_s_l.'</b> Listini SENZA Ditte.</div>';      
	  }  
	  
	  // Referenze senza ordine
	  $r_s_o = db_referenze_senza_ordine();
	  if($r_s_o==0){          
		$referenze_senza_ordine='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">REFERENZE -> ORDINI: <b>OK</b></div>';    
	  }else{
		$referenze_senza_ordine='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">REFERENZE -> ORDINI: : Ci sono <b>'.$r_s_o.'</b> Referenze SENZA Ordini.</div>';      
	  }
	  
	  
	  // Ccda postino totale
	  $coda_totale = quante_mail_coda_totale();
	  if($coda_totale==0){          
		$coda_totale='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">Coda Postino : <b>Vuota</b></div>';    
	  }else{
		$coda_totale='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">Coda Postino : Ci sono in totale <b>'.$coda_totale.'</b> Messaggi non consegnati</div>';      
	  }
	  
	  // Ccda postino effettiva
	  $coda_effettiva = quante_mail_coda_effettiva();
	  if($coda_effettiva==0){          
		$coda_effettiva='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">Coda Postino : <b>Vuota</b></div>';    
	  }else{
		$coda_effettiva='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">Coda Postino : Ci sono <b>'.$coda_effettiva.'</b> Messaggi non consegnati</div>';      
	  }
	  
	  // Status Mailer
	  
      $mailer_status = read_option_text(0,"MAILER");
	  //echo read_option_text(0,"MAILER");
      if($mailer_status=="ON"){          
		$mailer_status='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">Mailer  : <b>ON</b></div>';    
	  }else{
		$mailer_status='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">Mailer : <b>OFF</b></div>';      
	  }
	  
	  // Status Mailer
	  $debug_status = read_option_text(0,"DEBUG");
	  if($debug_status=="OFF"){          
		$debug_status='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">Debug  : <b>OFF</b></div>';    
	  }else{
		$debug_status='<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_alert">Debug : <b>ON</b></div>';      
	  }
	  
	  // Ultimo Geocode
	 $utenti_gc_valido = '<div style="padding:6px;margin-bottom:2px;" class="ui-corner-all campo_ok">Geocode Validi : <b>'.utenti_con_geocode_ok().'</b></div>';
	  
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  $h_table .= ' <div class="ui-widget-content ui-corner-all padding_6px">
				   <h3>Users :</h3>
				   '.$user_non_attivi.'
				   <hr>
				   
				   <table>
				   <tr>
				   <td width="50%">
				   <h3>Incongruenze relazioni database :</h3>
				   '.$dettagli_ordini_senza_ordine.'
				   '.$distribuzione_spesa_senza_dettagli_ordine.'
				   '.$amici_senza_referente.'
				   '.$articoli_senza_listino.'
				   '.$listini_senza_ditte.'
				   '.$referenze_senza_ordine.'
				   </td>
				   <td>
				   '.$debug_status.'
				   <h3>Posta :</h3>
				   '.$mailer_status.'
				   '.$coda_totale.'
				   '.$coda_effettiva.'
				   <h3>Geocoding :</h3> 
				   '.$utenti_gc_valido.'  
				   </td>
				   </tr>
				   </table>
				   </hr>	
					</div> 
				   ';
	  
	  $h_table .= amministra_opzioni_table("output",0); 
	   
	  // END TABELLA ----------------------------------------------------------------------------
	  
	  include ("amministra_main.php");
	  
	  
	  
}else{
	c1_go_away("?q=no_permission");
} 
?>