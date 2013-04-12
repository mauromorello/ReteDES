<?php

include_once ("../../rend.php"); 

//echo "ID LISTINO = $id_listino<br>
//	  DATA    = $data<br>
 //     GAS    = $gas";
      
//exit ;
$query = "SELECT * FROM retegas_listini WHERE id_ditte='$data' AND data_valido > now() AND tipo_listino=0;";
  $res = $db->sql_query($query);
  while ($row = $db->sql_fetchrow($res)){
  
  unset($show);
  
  if($row["is_privato"]<>0){
            if($gas==id_gas_user($row["id_utenti"])){
                 $show= true;
            }             
        }else{
            $show= true; 
        }
  if($show){
  
  if(articoli_n_in_listino($row["id_listini"])>0){
	    if(isset($id_listino)){
		    if($id_listino==$row["id_listini"]){
			    $selected = " SELECTED ";            
		    }else{
			    $selected = "";
		    }
		    
	    }  
	      
	    echo '<option value="'.$row["id_listini"].'" '.$selected.'>'.$row["descrizione_listini"].' ~ Di '.fullname_from_id($row["id_utenti"]).',
	      articoli : '.articoli_n_in_listino($row["id_listini"]).'
	     (scadenza : '.conv_only_date_from_db($row["data_valido"]).')</option>\\n';     
         }
  }
  
  
  
  }  //while


?>