<?php
  
include_once ("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$id_listino = articolo_id_listino($id);
		
	  
	  //-------------------------------------------------DELETE
		if($do=="del"){
			
		// Controllare che non abbia 
		//articoli pendenti
		//che il listino sia di user
		
		//if(1){$msg="Questa ditta ha associati degli ordini.<br> Impossibile eliminarla";}
		if(articoli_user($id)<>$id_user){
			$msg="Questo articolo non è stato inserito da te, oppure è già stato cancellato.<br> Impossibile eliminarlo";
			$id=$id_listino;
		include("../listini/listini_form.php");    
			exit;
		}
		if(articoli_in_ordine($id)<>0){
			$msg="Questo articolo è presente in qualche ordine.<br> Impossibile eliminarla";
			$id=$id_listino;
		include("../listini/listini_form.php");     
			exit;
		}
		
		$sql =  mysql_query("delete from  retegas_articoli where retegas_articoli.id_articoli=$id LIMIT 1;") or die ("Errore: ". mysql_error());    
				
		$msg = "Eliminazione riuscita";	
		$id=$id_listino;
		
		include("../listini/listini_form.php"); 	
		exit;    
		}
	  
	  
	  
	  //-------------------------------------------------------
	  
	  
	  

	  
	  // MENU APERTO
	  $menu_aperto=1;
		

	  
	  $h_table .= " 
					<div class=\"ui-state-error ui-corner-all padding_6px\" style=\"margin-bottom:20px\">
					<span class=\"ui-icon ui-icon-trash\" style=\"float:left; margin:0 7px 16px 0;\"></span>
					Stai per cancellare i dati di questa scheda : sei sicuro ?
					<a href=\"../articoli/articoli_form_delete.php?id=$id&do=del\" class=\"medium red awesome\">SI</a> 
					<a href=\"../listini/listini_form.php?id=$id_listino\" class=\"medium green awesome\">NO</a>
					</div>
";
		 
 include ("../articoli/articoli_form_core.php");  
 include ("../listini/listini_main.php");
 
 
}else{
	c1_go_away("?q=no_permission");
}
?>
