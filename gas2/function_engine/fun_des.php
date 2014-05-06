<?php
    function des_id_des_from_id_gas($id_gas){
        return db_val_q("id_gas",$id_gas,"id_des","retegas_gas");
    }
    function des_id_des_from_id_user($id_user){
        return des_id_des_from_id_gas(id_gas_user($id_user));
    }
    function des_nome_from_id_gas($id_gas){
        $id_des = db_val_q("id_gas",$id_gas,"id_des","retegas_gas");

        return db_val_q("id_des",$id_des,"des_descrizione","retegas_des");
    }