<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../ordini/ordini_renderer.php");
include_once ("../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//Se non ? settato il gas lo imposto come quello dell'utente
if(!isset($id_gas)){$id_gas = _USER_ID_GAS;}

if (!(_USER_PERMISSIONS & perm::puo_gestire_la_cassa)){
     pussa_via();
     exit;
}

if (ordine_inesistente($id_ordine)){
     go("sommario",_USER_ID,"Ordine insesistente");
}


//Cancellazione movimenti


if($do=="del"){
        
       
         $time_now = time();  
         (int)$validation = mimmo_decode($validation);  
         $time_diff = $time_now - $validation;
         
         if($time_diff>60){
             go("sommario",_USER_ID,"Il tempo per effettuare questa operazione ? scaduto, riparti daccapo.");
         }
        
         $pwd = md5($pwd);
         $result = $db->sql_query("SELECT userid,password FROM maaking_users WHERE userid='"._USER_ID."' AND password='$pwd'");

         if($db->sql_numrows($result) == 0){
         
          $msg .= "La password non ? stata riconosciuta";
          $e_pwd++;
               
         }
        
        
        
        $err =  $e_pwd;
        
        if($err==0){
        
        //INSERT IN CASSA UTENTI
        $my_query="DELETE FROM retegas_cassa_utenti WHERE id_ordine='$id_ordine' AND id_gas='"._USER_ID_GAS."';";                                         
        

        $result = $db->sql_query($my_query);
        $nrows = $db->sql_affectedrows($result);
        
        if (is_null($result)){
            $msg = "Errore nella cancallazione movimenti";
        }else{
            $msg = "OK, cancellati ".$nrows." movimenti";
            $ok++;
            log_me($id_ordine,_USER_ID,"CAS","DEL","Cancellazione movimenti cassa",$nrows,$my_query);
        };
            
            
        if($ok==1){
                go("sommario",_USER_ID,$msg);
            }else{
                go("sommario",_USER_ID,"E' successo qualcosa di imprevisto durante questa operazione");
            
            }
            
            
        }
        
    }











//Fine cancellazione


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Cancella tutti i movimenti cassa relativi a questo ordine";

$r->messaggio = $msg;

$h2 ='
        <div class="retegas_form ui-corner-all">
        <h3>Richiesta di conferma operazione</h3>
        <strong>Stai per eliminare tutti i movimenti relativi all\'ordine '.$id_ordine.' <br>
        </strong>
        <br>
        
        <form name="del_mov_ord" method="POST" action="">
        <div>
            <h4>1</h4>
            <label for="pwd">Inserisci la tua password</label>
            <input type="password" name="pwd" value="" size="20"></input>
            <h5 title="'.$help_pwd.'">Inf.</h5>
        </div>
        
        
        <div>
        <h4>3</h4>
        <label for="submit">infine... </label>
        <input type="submit" name="submit" value="Conferma operazione" align="center" >
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <input type="hidden" name="do" value="del">
        <input type="hidden" name="validation" value ="'.mimmo_encode(time()).'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>

        </form>
        
        <h5>
        oppure <a class="awesome medium red" href="">Abbandona</a>
        </h5>
        <br>
        <br>
        </div>
        ';


$r->contenuto = schedina_ordine($id_ordine).'<div class="rg_widget rg_widget_helper">'.$h2.'</div>';
echo $r->create_retegas();

//Distruggo l'oggetto r    
unset($r)   
?>