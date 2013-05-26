<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("../lib/securimage/securimage.php");




if(isset($do)AND $do=="req"){
     sanitize($password);
     $password = md5($password);
     
     sanitize($email);
     $securimage = new Securimage();

     if ($securimage->check($captcha_code) == true){ 
        $sql = "SELECT * FROM maaking_users WHERE password='$password' AND email='$email' LIMIT 1;";
        $res = $db->sql_query($sql);
        
        if($db->sql_numrows($res)>0){
                $row=$db->sql_fetchrow($res);
                $fullname = $row["fullname"];
                
               
                $messaggio .= "<p>Il tuo nome utente è <strong>".$row["username"]."</strong>
                               <p>
                               Ricordati che c'è differenza tra lettere maiuscole e minuscole.
                               </p>";

                manda_mail("["._SITE_NAME."]",_SITE_MAIL_LOG,$fullname,$email,"["._SITE_NAME."] - Username persa",$messaggio);
                sleep(1);
                //unlink("temp/pwd_$password.jpg");
                go("sommario",null,"","?q=44");
                die;
             
         }else{ //user
             $msg="L'accoppiata password / email non è stata riconosciuta";
             
         }
    
     }else{ //captcha
         $msg="Il codice letto nel disegnino non è giusto.";
     }
    
    
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Assegno il titolo che compare nella barra delle info
$r->title = "Username Dimenticata";

$r->messaggio = $msg; 
    

//Questo è il contenuto della pagina
$r->contenuto = '<div class="ui-widget-content ui-state-error padding_6px ui-corner-all">
Questa pagina permette di recuperare la propria username.
                </div>
                <br>
                 <form class="retegas_form ui-corner-all" method="POST">
                    <div>
                    <h4>1</h4>
                    <label for="username">Inserisci la tua password</label>
                    <input type="password" name="password" value="'.$password.'" size="50"></input>
                    <h5 title="'.$help_username.'">Inf.</h5>
                    </div>
                    
                    <div>
                    <h4>2</h4>
                    <label for="email">Inserisci la tua mail</label>
                    <input type="email" name="email" value="'.$email.'" size="50"></input>
                    <h5 title="'.$help_email.'">Inf.</h5>
                    </div>
                    
                    <div>
                    <h4>3</h4>
                    <label for="captcha_code">Inserisci quello che leggi nel disegno: <img id="captcha" src="../lib/securimage/securimage_show.php" alt="CAPTCHA Image" /></label>
                    <input type="text" name="captcha_code" value="" size="50" maxlenght="6"></input>
                    <h5 title="'.$help_captcha_code.'">Inf.</h5>
                    </div>
                    
                    <div>
                    <h4>4</h4>
                    <label for="submit">e infine... </label>
                    <input type="submit" name="submit" value="Ricordami la mia username" align="center" >
                    <input type="hidden" name="do" value="req">
                    <h5 title="'.$help_partenza.'">Inf.</h5>
                    </div>
                 </form>';
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);