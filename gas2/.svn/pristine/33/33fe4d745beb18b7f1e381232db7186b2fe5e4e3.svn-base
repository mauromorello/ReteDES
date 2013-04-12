<?php
include_once ("../rend.php");
if(is_numeric(_USER_ID)){

    $key_opt = sanitize($key_opt);
    $key_val = sanitize($key_val);   
             
                 
    $a = write_option_text(_USER_ID,$key_opt,$key_val);           
    if($key_val=="SI"){
        echo "<span style=\"color:green;\"><strong>OPZIONE ATTIVATA</strong></span>";
    }else{
        echo "<span style=\"color:red;\">OPZIONE NON ATTIVA</span>";
    }

           

}
?>