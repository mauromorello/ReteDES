<?php
include_once ("../../rend.php");
include_once ("../../retegas.class.php");  

if(_USER_LOGGED_IN){
    $vo = CAST_TO_FLOAT($ord_att,0) -  valore_totale_mio_ordine($id_ordine,_USER_ID);
    
           
    echo trim(number_format((cassa_utente_tutti_movimenti(_USER_ID) - $vo) ,2,".",""));
}

?>