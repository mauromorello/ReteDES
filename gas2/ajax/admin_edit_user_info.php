<?php

include_once ("../rend.php");
if(_USER_LOGGED_IN){ 
    if(isset($elementid)){
    
        $id_utente = CAST_TO_INT(mimmo_decode($elementid),0);

        if($id_utente==0){
            die();
        }
        
        $newvalue = sanitize($newvalue);
        

            if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
                switch ($type){
                        case "email":
                          $sql ="UPDATE  `maaking_users` SET  `email` =  '$newvalue' WHERE  `maaking_users`.`userid` ='$id_utente' LIMIT 1 ;";    
                          $db->sql_query($sql);
                          echo $newvalue;
                          die();
                       break; 
                    
                       case "fullname":
                          $sql ="UPDATE  `maaking_users` SET  `fullname` =  '$newvalue' WHERE  `maaking_users`.`userid` ='$id_utente' LIMIT 1 ;";    
                          $db->sql_query($sql);
                          echo $newvalue;
                          die();
                       break;
                       case "address_1":
                          $sql ="UPDATE  `maaking_users` SET  `country` =  '$newvalue' WHERE  `maaking_users`.`userid` ='$id_utente' LIMIT 1 ;";    
                          $db->sql_query($sql);
                          echo $newvalue;
                          die();
                       break;
                       case "address_2":
                          $sql ="UPDATE  `maaking_users` SET  `city` =  '$newvalue' WHERE  `maaking_users`.`userid` ='$id_utente' LIMIT 1 ;";    
                          $db->sql_query($sql);
                          echo $newvalue;
                          die();
                       break;
                       
                }
            }
    }
}
?>
