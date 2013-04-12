<?php
 include ("../rend.php");

 //echo $rd_code."<br>";
 //echo $rd_tipo."<br>";
 
  
 if(!isset($rd_code)){echo "MISSING CODE";die();}
 if(!isset($rd_tipo)){echo "MISSING TYPE";die();}

 $id_gas = wp_id_gas_from_code($rd_code);
 //echo $id_gas."<br>";
 
 if($id_gas==0 | $id_gas==""){
    echo "Codice GAS non riconosciuto.";die();    
 }
 


 

 switch($rd_tipo){
 case "OA":
 $my_query="SELECT retegas_ordini.id_ordini, 
            retegas_ordini.descrizione_ordini, 
            retegas_listini.descrizione_listini, 
            retegas_ditte.descrizione_ditte, 
            retegas_ordini.data_chiusura, 
            retegas_gas.descrizione_gas, 
            retegas_referenze.id_gas_referenze, 
            maaking_users.userid, 
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini)INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas WHERE (((retegas_ordini.data_chiusura)>NOW())AND ((retegas_ordini.data_apertura)<NOW()) AND ((retegas_referenze.id_gas_referenze)='".$id_gas."')) ORDER BY retegas_ordini.data_chiusura ASC ;";
      $result = $db->sql_query($my_query);
	
         while ($row = $db->sql_fetchrow($result)){
         $riga++;
        
             if(id_referente_ordine_proprio_gas($row["id_ordini"],$id_gas)>0){
                $pal = '';
             }else{
                $pal = '<strong>(*)</strong> '; 
             }
             $h.=  $pal.'<strong style="font-size:.95em;"><a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'">'.$row["descrizione_ordini"].'</a></strong><span style="font-size:.8em;">, da '.ditta_nome_from_listino($row["id_listini"]).', tot. '.round(valore_totale_ordine($row["id_ordini"]),2) .'Eu.</span><br>';
         }
        $h.="<br><strong>(*) Manca il referente per il proprio gas</strong>";
        break;
case "OF" : 
 $my_query="SELECT retegas_ordini.id_ordini, 
            retegas_ordini.descrizione_ordini, 
            retegas_listini.descrizione_listini, 
            retegas_ditte.descrizione_ditte, 
            retegas_ordini.data_chiusura, 
            retegas_gas.descrizione_gas, 
            retegas_referenze.id_gas_referenze, 
            maaking_users.userid, 
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE (((retegas_ordini.data_apertura)>NOW()) AND ((retegas_referenze.id_gas_referenze)='".$id_gas."'))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
      $result = $db->sql_query($my_query);
        // echo $my_query;
         while ($row = $db->sql_fetchrow($result)){
         $riga++;
             $h.= '<strong style="font-size:1em;"><a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'">'.$row["descrizione_ordini"].'</a></strong><span style="font-size:.75em;">, da '.ditta_nome_from_listino($row["id_listini"])."</span><br>";
         }
        
        break;
case "OC" : 
 $my_query="SELECT retegas_ordini.id_ordini, 
            retegas_ordini.descrizione_ordini, 
            retegas_listini.descrizione_listini, 
            retegas_ditte.descrizione_ditte, 
            retegas_ordini.data_chiusura, 
            retegas_gas.descrizione_gas, 
            retegas_referenze.id_gas_referenze, 
            maaking_users.userid, 
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE (((retegas_ordini.data_chiusura)<NOW()) AND ((retegas_referenze.id_gas_referenze)='".$id_gas."'))
            ORDER BY retegas_ordini.data_chiusura DESC ;";
      $result = $db->sql_query($my_query);
        // echo $my_query;
         while ($row = $db->sql_fetchrow($result)){
         $riga++;
             $h.= '<strong style="font-size:1em;"><a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'">'.$row["descrizione_ordini"].'</a></strong><span style="font-size:.75em;">, da '.ditta_nome_from_listino($row["id_listini"]).", chiuso il ".conv_date_from_db($row["data_chiusura"])."</span><br>";
         }
        
        break;                
 }
 
 echo $h;