<?php

//OPTION_USER

function read_option_integer($id_user,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_user'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_INT($row["valore_int"]);         
            
    
}
function read_option_text($id_user,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_user'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_STRING($row["valore_text"]);         
            
    
}
function read_option_decimal($id_user,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_user'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_FLOAT($row["valore_real"]);         
            
    
}
function read_option_note($id_user,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_user'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return ($row["note_1"]);         
            
    
}

function check_option_exist($id_user,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_user'
            AND
            chiave = '$chiave';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;         
            
    
}
function delete_option_text($id_user,$chiave){
    global $db;
    $qry = "DELETE FROM retegas_options 
            WHERE
            id_user = '$id_user'
            AND
            chiave = '$chiave';";
   $res = $db->sql_query($qry);
       
}

function write_option_integer($id_user,$chiave,$valore){
    
    global $db;
    
    $valore = CAST_TO_INT($valore);
    
    if(check_option_exist($id_user,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_user` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_user',  
                             '$chiave',  
                             '', 
                             CURRENT_TIMESTAMP,  
                             '$valore', 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_int` =  '$valore' 
                WHERE  
                id_user = '$id_user'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $row;         
            
    
}
function write_option_text($id_user,$chiave,$valore){
    
    global $db;
    
    $id_user = CAST_TO_INT($id_user);
    $valore = CAST_TO_STRING($valore);
    
    if(check_option_exist($id_user,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_user` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_user',  
                             '$chiave',  
                             '$valore', 
                             CURRENT_TIMESTAMP,  
                             '', 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_text` =  '$valore' 
                WHERE  
                id_user = '$id_user'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;         
            
    
}
function write_option_note($id_user,$chiave,$valore){
    
    global $db;
    
    $id_user = CAST_TO_INT($id_user);
    $valore = sanitize($valore);
    
    if(check_option_exist($id_user,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_user` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_user',  
                             '$chiave',  
                             null, 
                             CURRENT_TIMESTAMP,  
                             '', 
                             NULL ,  
                             '$valore'
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `note_1` =  '$valore' 
                WHERE  
                id_user = '$id_user'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;         
            
    
}
function write_option_decimal($id_user,$chiave,$valore){
    
    global $db;
    
    $valore = CAST_TO_FLOAT($valore);
    
    if(check_option_exist($id_user,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_user` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_user',  
                             '$chiave',  
                             '', 
                             CURRENT_TIMESTAMP,  
                             '', 
                             $valore ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_real` =  '$valore' 
                WHERE  
                id_user = '$id_user'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $row;         
            
    
}



//OPTION GAS

//OLD
function read_option_gas_text($id_gas,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            valore_int = '$id_gas'
            AND
            id_user='0'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_STRING($row["valore_text"]);         
            
    
}
function write_option_gas_text($id_gas,$chiave,$valore){
    
    global $db;
    
    $id_gas = CAST_TO_INT($id_gas);
    $valore = CAST_TO_STRING($valore);
    
    if(check_option_gas_exist($id_gas,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_user` ,
                        
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '0',
                               
                             '$chiave',  
                             '$valore', 
                             CURRENT_TIMESTAMP,  
                             '$id_gas', 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_text` =  '$valore' 
                WHERE  
                id_user = '0'
                AND
                valore_int = '$id_gas'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;         
            
    
}
function check_option_gas_exist($id_gas,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '0'
            AND
            valore_int='$id_gas'
            AND
            chiave = '$chiave';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;         
            
    
}


//NEW
function read_option_gas_text_new($id_gas,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_gas = '$id_gas'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_STRING($row["valore_text"]);         
            
    
}
function write_option_gas_text_new($id_gas,$chiave,$valore){
    
    global $db;
    
    $id_gas = CAST_TO_INT($id_gas);
    $valore = CAST_TO_STRING($valore);
    
    if(check_option_gas_exist_new($id_gas,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_gas` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_gas',
                             '$chiave',  
                             '$valore', 
                             CURRENT_TIMESTAMP,  
                             NULL, 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_text` =  '$valore' 
                WHERE  
                id_gas = '$id_gas'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;         
            
    
}
function check_option_gas_exist_new($id_gas,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_gas='$id_gas'
            AND
            chiave = '$chiave';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;         
            
    
}

//BLACKLIST_ORDERS
function write_option_order_blacklist($id_gas,$id_ordine){
    
    global $db;
    
    $id_gas = CAST_TO_INT($id_gas);
    $valore = CAST_TO_STRING($valore);
    
    if(check_option_order_blacklist($id_gas,$id_ordine)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_gas` ,
                        `chiave` ,
                        `id_ordine` ,
                        `id_user` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_gas',
                             'BLACKLIST',  
                             '$id_ordine',
                             '"._USER_ID."', 
                             CURRENT_TIMESTAMP,  
                             NULL, 
                             NULL,  
                             ''
                    );";    
    $res = $db->sql_query($qry);
    $rows = $db->sql_affectedrows($res);
    }
    
    
   
   
   return $rows;         
            
    
}
function check_option_order_blacklist($id_gas,$id_ordine){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_gas='$id_gas'
            AND
            id_ordine='$id_ordine'
            AND
            chiave = 'BLACKLIST';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;         
            
    
}


//DES
function read_option_des_integer($id_des,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_des = '$id_des'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_INT($row["valore_int"]);         
            
    
}
function read_option_des_text($id_des,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_des = '$id_des'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_STRING($row["valore_text"]);         
            
    
}
function read_option_des_decimal($id_des,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_des = '$id_des'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_FLOAT($row["valore_real"]);         
            
    
}
function read_option_des_note($id_des,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_des = '$id_des'
            AND
            chiave = '$chiave'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return ($row["note_1"]);         
            
    
}

function check_option_des_exist($id_des,$chiave){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_des = '$id_des'
            AND
            chiave = '$chiave';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;         
            
    
}
function delete_option_des_text($id_des,$chiave){
    global $db;
    $qry = "DELETE FROM retegas_options 
            WHERE
            id_des = '$id_des'
            AND
            chiave = '$chiave';";
   $res = $db->sql_query($qry);
       
}

function write_option_des_integer($id_des,$chiave,$valore){
    
    global $db;
    
    $valore = CAST_TO_INT($valore);
    
    if(check_option_des_exist($id_des,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_des` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_des',  
                             '$chiave',  
                             '', 
                             CURRENT_TIMESTAMP,  
                             '$valore', 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_int` =  '$valore' 
                WHERE  
                id_des = '$id_des'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $row;         
            
    
}
function write_option_des_text($id_des,$chiave,$valore){
    
    global $db;
    
    $id_des = CAST_TO_INT($id_des);
    $valore = CAST_TO_STRING($valore);
    
    if(check_option_des_exist($id_des,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_des` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_des',  
                             '$chiave',  
                             '$valore', 
                             CURRENT_TIMESTAMP,  
                             '', 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_text` =  '$valore' 
                WHERE  
                id_des = '$id_des'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;         
            
    
}
function write_option_des_note($id_des,$chiave,$valore){
    
    global $db;
    
    $id_des = CAST_TO_INT($id_des);
    $valore = sanitize($valore);
    
    if(check_option_des_exist($id_des,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_des` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_des',  
                             '$chiave',  
                             null, 
                             CURRENT_TIMESTAMP,  
                             '', 
                             NULL ,  
                             '$valore'
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `note_1` =  '$valore' 
                WHERE  
                id_des = '$id_des'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;         
            
    
}
function write_option_des_decimal($id_des,$chiave,$valore){
    
    global $db;
    
    $valore = CAST_TO_FLOAT($valore);
    
    if(check_option_des_exist($id_des,$chiave)==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_des` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_des',  
                             '$chiave',  
                             '', 
                             CURRENT_TIMESTAMP,  
                             '', 
                             $valore ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_text` =  '$valore' 
                WHERE  
                id_des = '$id_des'
                AND
                chiave = '$chiave' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $row;         
            
    
}

//AIUTO-ORDINI
function check_option_aiuto_ordine($id_ordine,$id_utente){
    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_ordine = '$id_ordine'
            AND
            id_user = '$id_utente'
            AND
            chiave = 'AIUTO_ORDINI';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;    
} 
function write_option_aiuto_ordine($id_ordine,$id_utente,$ruolo){
    global $db;
    
    $id_utente = CAST_TO_INT($id_utente);
    $id_ordine = CAST_TO_INT($id_ordine);
    $ruolo = CAST_TO_STRING($ruolo);
    
    $esiste_opzione = check_option_aiuto_ordine($id_ordine,$id_utente);
    
    if($esiste_opzione==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_user` ,
                        `id_ordine` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_utente',
                             '$id_ordine',  
                             'AIUTO_ORDINI',  
                             '$ruolo', 
                             CURRENT_TIMESTAMP,  
                             '0', 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_text` =  '$ruolo' 
                WHERE  
                id_user = '$id_utente'
                AND
                chiave = 'AIUTO_ORDINI'
                AND
                id_ordine = '$id_ordine' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;     
}
function activate_option_aiuto_ordine($id_ordine,$id_utente){
        global $db;
    
    $id_utente = CAST_TO_INT($id_utente);
    $id_ordine = CAST_TO_INT($id_ordine);
    $ruolo = CAST_TO_STRING($ruolo);
    
    if(check_option_aiuto_ordine($id_ordine,$id_utente)==0){
   
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_int` =  '1' 
                WHERE  
                id_user = '$id_utente'
                AND
                chiave = 'AIUTO_ORDINI'
                AND
                id_ordine = '$id_ordine' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;
}
function deactivate_option_aiuto_ordine($id_ordine,$id_utente){
        global $db;
    
    $id_utente = CAST_TO_INT($id_utente);
    $id_ordine = CAST_TO_INT($id_ordine);
    $ruolo = CAST_TO_STRING($ruolo);
    
    if(check_option_aiuto_ordine($id_ordine,$id_utente)==0){
   
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_int` =  '0'
                WHERE  
                id_user = '$id_utente'
                AND
                chiave = 'AIUTO_ORDINI'
                AND
                id_ordine = '$id_ordine' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;
}
function refuse_option_aiuto_ordine($id_ordine,$id_utente){
        global $db;
    
    $id_utente = CAST_TO_INT($id_utente);
    $id_ordine = CAST_TO_INT($id_ordine);
    
    
    if(check_option_aiuto_ordine($id_ordine,$id_utente)==0){
   
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_int` =  '2'
                WHERE  
                id_user = '$id_utente'
                AND
                chiave = 'AIUTO_ORDINI'
                AND
                id_ordine = '$id_ordine' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;
}
function read_option_aiuto_ordine($id_ordine,$id_utente){
    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_utente'
            AND
            chiave = 'AIUTO_ORDINI'
            AND
            id_ordine = '$id_ordine'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_STRING($row["valore_text"]);
}
function delete_option_aiuto_ordine($id_ordine,$id_utente){
    global $db;
    $qry = "DELETE FROM retegas_options 
            WHERE
            id_user = '$id_utente'
            AND
            id_ordine = '$id_ordine'
            AND
            chiave = 'AIUTO_ORDINI';";
   $res = $db->sql_query($qry);
}
function crea_lista_aiuti_ordine_attivi($id_ordine){
    global $db;
    $sql = "SELECT * FROM retegas_options WHERE
            id_ordine = '$id_ordine'
            AND
            chiave ='AIUTO_ORDINI'
            AND
            valore_int <2 ;";
    $res = $db->sql_query($sql);
    if ($db->sql_numrows($res)>0){
        
        $h .= "<ul style=\"list-style-type:none; margin-left:-3.6em;\">";
        while ($row = $db->sql_fetchrow($res_o)){ 
            $id_ut= $row["id_user"];
            $fullname = fullname_from_id($id_ut);
            $ruolo = $row["valore_text"];
            if($row["valore_int"]==1){
                $colore="#000000";
                $pal = pallino("green",10);
            }else{
                $colore="#969696";
                $pal = pallino("grey",10);
            } 
            $h .="<li style=\"color:$colore\">$pal <a href=\"".$RG_addr["user_form_public"]."?id_utente=".mimmo_encode($id_ut)."\">$fullname</a> <span class=\"small_link\">$ruolo</span></li>";
        }
        $h .= "</ul>";
        return $h;
        
    }else{
        return "Nessuno;";
    }        
    
    
}

//PRENOTAZIONI
function check_option_prenotazione_ordine($id_ordine,$id_utente){
    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_ordine = '$id_ordine'
            AND
            id_user = '$id_utente'
            AND
            chiave = 'PRENOTAZIONE_ORDINI';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;    
} 
function write_option_prenotazione_ordine($id_ordine,$id_utente,$text){
    global $db;
    
    $id_utente = CAST_TO_INT($id_utente);
    $id_ordine = CAST_TO_INT($id_ordine);
    $ruolo = CAST_TO_STRING($ruolo);
    
    $esiste_opzione = check_option_prenotazione_ordine($id_ordine,$id_utente);
    
    if($esiste_opzione==0){
            $qry = "INSERT INTO  
                    `retegas_options` (
                        `id_option` ,
                        `id_user` ,
                        `id_ordine` ,
                        `chiave` ,
                        `valore_text` ,
                        `timbro` ,
                        `valore_int` ,
                        `valore_real` ,
                        `note_1`
                        )
                    VALUES (
                             NULL ,  
                             '$id_utente',
                             '$id_ordine',  
                             'PRENOTAZIONE_ORDINI',  
                             '$text', 
                             CURRENT_TIMESTAMP,  
                             '0', 
                             NULL ,  
                             ''
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_text` =  '$text' 
                WHERE  
                id_user = '$id_utente'
                AND
                chiave = 'PRENOTAZIONE_ORDINI'
                AND
                id_ordine = '$id_ordine' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;     
}
function read_option_prenotazione_ordine($id_ordine,$id_utente){
    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_utente'
            AND
            chiave = 'PRENOTAZIONE_ORDINI'
            AND
            id_ordine = '$id_ordine'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_STRING($row["valore_text"]);
}
function delete_option_prenotazione_ordine($id_ordine,$id_utente){
    global $db;
    $qry = "DELETE FROM retegas_options 
            WHERE
            id_user = '$id_utente'
            AND
            id_ordine = '$id_ordine'
            AND
            chiave = 'PRENOTAZIONE_ORDINI';";
   $res = $db->sql_query($qry);
}
function crea_lista_prenotazioni_attive($id_utente){
    global $db, $RG_addr;
    $sql = "SELECT * FROM retegas_options WHERE
            id_user = '$id_utente'
            AND
            chiave ='PRENOTAZIONE_ORDINI';";
    $res = $db->sql_query($sql);
    if ($db->sql_numrows($res)>0){
        
        $h .= "<ul style=\"list-style-type:none; margin-left:-3.6em;\">";
        
        while ($row = $db->sql_fetchrow($res)){ 
            $id_ordine= $row["id_ordine"];
            $valore_ordine = valore_totale_lordo_mio_ordine($id_ordine,_USER_ID);
            $valore_totale = $valore_totale + $valore_ordine;
            $valore_ordine = _nf($valore_ordine);
            $nome_ordine = descrizione_ordine_from_id_ordine($id_ordine);
            $h .="<li style=\"color:$colore\">Ord. $id_ordine, <strong><a href=\"".$RG_addr["ordini_form_new"]."?id_ordine=".$id_ordine."\"> $nome_ordine</a></strong>, per $valore_ordine Eu.</li>";
        }
        $h .= "</ul>";
        $valore_aggiunta = ($valore_totale / 100) * _GAS_COPERTURA_CASSA; 
        $valore_totale=_nf($valore_totale+$valore_aggiunta);
        $h .= "Totale : $valore_totale Eu<br>
               <cite>NB : Nel valore totale è compresa la percentuale di copertura cassa che il tuo gas applica.</cite> ";
        return $h;
        
    }else{
        return "";
    }        
    
    
}


//GLOBAL OPTIONS
function check_option_exist_global($where){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            $where;";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;         
            
    
}
function read_option_text_global($where){

    global $db;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            $where
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_STRING($row["valore_text"]);         
            
    
}

//ALTRE
function load_user_options($id_user){
    global $db,$RG_addr;
    $qry = "SELECT * FROM retegas_options 
            WHERE
            id_user = '$id_user'
            OR
            (id_user='0'
            AND
            valore_int='"._USER_ID_GAS."');";
   $res = $db->sql_query($qry);
   while ($row = mysql_fetch_array($res)){
        //echo $row["chiave"]." --> ".$row["valore_text"]."<br>";
        switch ($row["chiave"]){
          case"MSG";
            define("_USER_HAVE_MSG",true);
            define("_USER_MSG",$row["valore_text"]);
            //delete_option_text($id_user,"MSG");
          break;
          case"LANG";
            define("_USER_LANGUAGE",$row["valore_text"]);
          break;
          case"_USER_OPT_SEND_MAIL";
            define("_USER_OPT_SEND_MAIL",$row["valore_text"]);
          break;
          case"_USER_OPT_NO_HEADER";
            define("_USER_OPT_NO_HEADER",$row["valore_text"]);
          break;
          case"_USER_OPT_SHOW_DEBUG";
            define("_USER_OPT_SHOW_DEBUG",CAST_TO_INT($row["valore_int"]));
            break;
          case"_USER_OPT_NO_SITE_HEADER";
            define("_USER_OPT_NO_SITE_HEADER",$row["valore_text"]);
          break;
          case"_USER_OPT_THEME";
            define("_USER_OPT_THEME",$row["valore_text"]);
          break;
          case"_USER_OPT_DECIMALS";
            define("_USER_OPT_DECIMALS",CAST_TO_INT($row["valore_int"],1,4));
          break;
          case"DONATE";
            define("_USER_DONATION",CAST_TO_FLOAT($row["valore_real"]));
          break;
          case"_USER_USA_CASSA";
            if($row["valore_text"]=="SI"){
                define("_USER_USA_CASSA",true);    
            }else{
                define("_USER_USA_CASSA",false);
            }  
          break;
          case"_USER_USA_TOOLTIPS";
            if($row["valore_text"]=="SI"){
                define("_USER_USA_TOOLTIPS",true);    
            }else{
                define("_USER_USA_TOOLTIPS",false);
            }  
          break;
          //CSV
          case"_USER_CSV_SEPARATOR";
            define("_USER_CSV_SEPARATOR",CAST_TO_STRING($row["valore_text"],1));
          break;
          case"_USER_CSV_DELIMITER";
            define("_USER_CSV_DELIMITER",CAST_TO_STRING($row["valore_text"],1));
          break;
          case"_USER_CSV_EOL";
            if($row["valore_text"]=="n"){
                define("_USER_CSV_EOL","\n");    
            }
            if($row["valore_text"]=="rn"){
                define("_USER_CSV_EOL","\r\n");
            }
          break;
          case"_USER_CSV_ZERO";
            if($row["valore_text"]=="0"){
                define("_USER_CSV_ZERO",0);    
            }else{
                define("_USER_CSV_ZERO","");
            }
          break;
          
          
          
          
          case"_USER_CARATTERE_DECIMALE";
            if($row["valore_text"]=="."){
                define("_USER_CARATTERE_DECIMALE",".");    
            }else{
                define("_USER_CARATTERE_DECIMALE",",");
            }  
          break;
          
          case"_GAS_SITE_LOGO";
            define("_GAS_SITE_LOGO",($row["valore_text"]));
          break;
          
          case"_GAS_USA_CASSA";
          if($row["valore_text"]=="SI"){
                define("_GAS_USA_CASSA",true);    
            }else{
                define("_GAS_USA_CASSA",false);
            }
          break;
          
          /*case"_GAS_PUO_PART_ORD_EST";
          if($row["valore_text"]=="SI"){
                define("_GAS_PUO_PART_ORD_EST",true);    
            }else{
                define("_GAS_PUO_PART_ORD_EST",false);
            }
          break;
          
          case"_GAS_PUO_COND_ORD_EST";
          if($row["valore_text"]=="SI"){
                define("_GAS_PUO_COND_ORD_EST",true);    
            }else{
                define("_GAS_PUO_COND_ORD_EST",false);
            }
          break;
         */ 
          case"_GAS_COPERTURA_CASSA";
          if(CAST_TO_INT($row["valore_text"],0,100)>0){
                define("_GAS_COPERTURA_CASSA",CAST_TO_INT($row["valore_text"],0,100));    
            }else{
                define("_GAS_COPERTURA_CASSA",0);
            }
          break;
          
          case"_GAS_CASSA_MIN_LEVEL";
          if(CAST_TO_FLOAT($row["valore_text"],0,1000)>0){
                define("_GAS_CASSA_MIN_LEVEL",CAST_TO_FLOAT($row["valore_text"],0,1000));    
            }else{
                define("_GAS_CASSA_MIN_LEVEL",0);
            }
          break;
          
          case"_SITE_SHOW_USERID";
          if($row["valore_text"]=="SI"){
                define("_SITE_SHOW_USERID",true);    
            }else{
                define("_SITE_SHOW_USERID",false);
            }
          break;
                       
        }
   }
   if(!defined("_USER_HAVE_MSG")){define("_USER_HAVE_MSG",false);}
   if(!defined("_USER_MSG")){define("_USER_MSG",false);}
   if(!defined("_USER_OPT_NO_HEADER")){define("_USER_OPT_NO_HEADER","NO");}
   if(!defined("_USER_OPT_THEME")){define("_USER_OPT_THEME",false);}
   if(!defined("_USER_OPT_NO_SITE_HEADER")){define("_USER_OPT_NO_SITE_HEADER","NO");}
   if(!defined("_USER_LANGUAGE")){define("_USER_LANGUAGE","NON DEFINITA");}
   if(!defined("_USER_OPT_SEND_MAIL")){define("_USER_OPT_SEND_MAIL","SI");}
   if(!defined("_USER_OPT_SHOW_DEBUG")){define("_USER_OPT_SHOW_DEBUG",0);}
   if(!defined("_USER_OPT_DECIMALS")){define("_USER_OPT_DECIMALS",2);}
   if(!defined("_USER_USA_CASSA")){define("_USER_USA_CASSA",false);}
   if(!defined("_USER_USA_TOOLTIPS")){define("_USER_USA_CASSA",true);}
   if(!defined("_USER_CARATTERE_DECIMALE")){define("_USER_CARATTERE_DECIMALE",".");}
   if(!defined("_USER_DONATION")){define("_USER_DONATION",0);}
   //CSV
   if(!defined("_USER_CSV_DELIMITER")){define("_USER_CSV_DELIMITER",'"');}
   if(!defined("_USER_CSV_SEPARATOR")){define("_USER_CSV_SEPARATOR",',');}
   if(!defined("_USER_CSV_EOL")){define("_USER_CSV_EOL","\r\n");}
   if(!defined("_USER_CSV_ZERO")){define("_USER_CSV_ZERO",0);}
   
   //GAS
   if(!defined("_GAS_USA_CASSA")){define("_GAS_USA_CASSA",false);}
   //if(!defined("_GAS_PUO_PART_ORD_EST")){define("_GAS_PUO_PART_ORD_EST",false);}
   //if(!defined("_GAS_PUO_COND_ORD_EST")){define("_GAS_PUO_COND_ORD_EST",false);}
   if(!defined("_GAS_SITE_LOGO")){define("_GAS_SITE_LOGO","");}    
   if(!defined("_GAS_COPERTURA_CASSA")){define("_GAS_COPERTURA_CASSA",0);}
   if(!defined("_GAS_CASSA_MIN_LEVEL")){define("_GAS_CASSA_MIN_LEVEL",0);}
   if(!defined("_SITE_SHOW_USERID")){define("_SITE_SHOW_USERID",false);}
   
   define("_USER_ID_DES",db_val_q("id_gas",_USER_ID_GAS,"id_des","retegas_gas"));
   define("_USER_DES_NAME",db_val_q("id_des",_USER_ID_DES,"des_descrizione","retegas_des"));
   
   define("_USER_DES_LAT",db_val_q("id_des",_USER_ID_DES,"des_lat","retegas_des"));
   define("_USER_DES_LNG",db_val_q("id_des",_USER_ID_DES,"des_lng","retegas_des"));
   define("_USER_DES_ZOOM",db_val_q("id_des",_USER_ID_DES,"des_zoom","retegas_des"));
   
   if(check_option_des_exist(_USER_ID_DES,"_DES_SITE_LOGO")>0){
       define("_DES_SITE_LOGO",read_option_des_text(_USER_ID_DES,"_DES_SITE_LOGO"));
   }else{
       define("_DES_SITE_LOGO",$RG_addr["img_logo_retedes"]);
   }
   
   if(read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_CHECK_MIN_LEVEL")=="SI"){
       define("_GAS_CASSA_CHECK_MIN_LEVEL",true);
   }else{
       define("_GAS_CASSA_CHECK_MIN_LEVEL",false);
   }
   
   if(read_option_gas_text(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST")=="SI"){
       define("_GAS_PUO_PART_ORD_EST",true);
   }else{
       define("_GAS_PUO_PART_ORD_EST",false);
   }
   
   if(read_option_gas_text(_USER_ID_GAS,"_GAS_PUO_PART_ORD_EST")=="SI"){
       define("_GAS_PUO_COND_ORD_EST",true);
   }else{
       define("_GAS_PUO_COND_ORD_EST",false);
   }

}

function wp_id_gas_from_code($code){
    global $db;
    $qry = "SELECT id_gas FROM retegas_options 
            WHERE
            chiave = '_WPID_GAS'
            AND
            valore_text = '$code';";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return $row["id_gas"];    
}
 