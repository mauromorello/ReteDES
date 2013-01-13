<?php

include_once ("../rend.php");
if(_USER_LOGGED_IN){ 
    if(isset($elementid)){
    
        $id_ditta = CAST_TO_INT($elementid);

        if($id_ditta==0){
            die();
        }
        
            $newvalue=sanitize($newvalue);

            if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
                switch ($type){
                       case "address_ditte":
                          $sql ="UPDATE  `retegas_ditte` SET  `indirizzo` =  '$newvalue' WHERE  `retegas_ditte`.`id_ditte` ='$id_ditta' LIMIT 1 ;";    
                          $db->sql_query($sql);
                          sleep(1);
                          $res_geocode = geocode_ditte_table("SELECT * FROM retegas_ditte WHERE id_ditte='$id_ditta';");
                          echo $newvalue;
                          die();
                       break;
                       case "tags_ditte":
                          $sql ="UPDATE  `retegas_ditte` SET  `tag_ditte` =  '$newvalue' WHERE  `retegas_ditte`.`id_ditte` ='$id_ditta' LIMIT 1 ;";    
                          $db->sql_query($sql);
                          echo $newvalue;
                          die();
                       break;
                       
                }
            }
    }
}
?>
