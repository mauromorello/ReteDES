<?php


include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		
		//permessi
		$permission = (int)$cookie_read[6]; 
		 if(!($permission & perm::puo_partecipare_ordini)){
			$q = "not_allowed";
			include ("../index.php");
			exit;            
			}
		
		
		if(ordine_io_cosa_sono($id,$id_user)==0){
			pussa_via();   
		exit;    
		}
		
		
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);
		$nome_ordine = descrizione_ordine_from_id_ordine($id);
	
	
	
	
	  // Campi e intestazioni
	  include ("ordini_aperti_sql_core.php");
	  
	  // menu:
	  $menu_aperto=3;
	  
	  
	  
if($do=="add_ref"){ 
	  //aggiungi referenza
	 if(!empty($v7)){  
	 $cookie_read = explode("|", base64_decode($user));
	 
	 
	 $result=$db->sql_query("UPDATE retegas_referenze SET retegas_referenze.id_utente_referenze = '$id_user'
							WHERE (((retegas_referenze.id_ordine_referenze)='$id_ordine') AND ((retegas_referenze.id_gas_referenze)='$gas'));");
		$msg .= "Referenza aggiunta.<br>Ora tu ed il tuo GAS potrete ordinare articoli da questo ordine."; 
		
        header("Location: ".$RG_addr["ordini_form"]."?id_ordine=$id&msg=4");
        //include("ordini_aperti_table.php");
		exit;
	 }else{
		  $msg = "Per diventare Referente del tuo GAS devi prima accettare le condizioni di partecipazione, spuntando la casellina sopra il pulsante verde";
		  unset($do);
		  include("ordini_aperti_diventa_referente_form.php");
		  exit;
	 }
	 } 
	 
	  
	  //FIne aggiungi referenza
								 
	  
	  
$output_html .= "
					<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">DIVENTA REFERENTE DEL TUO GAS PER QUESTO ORDINE ($nome_ordine)</div>
						
					"; 
//----------------nuovo form
$output_html .= "<form method=\"POST\" action=\"ordini_aperti_diventa_referente_form.php\">"; 
$output_html .="<table>
   <tr class=\"odd\">
	  <td>
		 Accetto di diventare referente di questo ordine per il MIO gas. Questo vuol dire che dovrò occuparmi di raccogliere gli ordini che mi perverranno dagli iscritti al mio GAS, e dovrò gestire il recupero della merce ed i pagamenti che verranno effettuati al gestore principale dell'ordine. Cliccando la casella qui sotto mi impegno a rispettare gli impegni presi.
	  </td>
   </tr>   
<tr>	
	  <td>Accetto<input type=\"checkbox\" name=\"v7\" value=\"1\">
	  </td>
   

	<tr>
	</table>  
	 

		<input type=\"hidden\" name=\"do\" value=\"add_ref\">
		<input type=\"hidden\" name=\"id\" value=\"$id\">
		<center>
		<input class =\"large green awesome\" style=\"margin:20px;\" type=\"submit\" value=\"SI, DIVENTERO' IL  REFERENTE !!\">
		</form>
		<br>
		<form method=\"POST\" action=\"ordini_aperti_table.php\">
		<center>
		<input class =\"large red awesome\" style=\"margin:20px;\"type=\"submit\" value=\"NO GRAZIE, magari un'altra volta...\">
		</form>  
	  
   
   
";
	$h_table =  $output_html;
	   
	  // HEADER HTML
	  //$msg ="Pagina non ancora funzionante";
	  $posizione ="ORDINI APERTI -> <b>Diventa referente</b>";
	  include ("ordini_aperti_main.php");
 
}else{
	pussa_via();
} 
?>