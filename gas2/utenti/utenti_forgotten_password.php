<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("../lib/securimage/securimage.php");




if(isset($do)AND $do=="req"){
     sanitize($username);
     sanitize($email);
     $securimage = new Securimage();

     if ($securimage->check($captcha_code) == true){ 
        $sql = "SELECT * FROM maaking_users WHERE username='$username' AND email='$email'";
        if($db->sql_numrows($db->sql_query($sql))>0){
                $securimage = new Securimage();
                $args = array(
                                'length'                =>   _SITE_LOST_PASSWORD_CHARS,
                                'alpha_upper_include'   =>   TRUE,
                                'alpha_lower_include'   =>   TRUE,
                                'number_include'        =>   TRUE,
                                'symbol_include'        =>   FALSE,
                                );
                $object = new chip_password_generator( $args );
                $password = $object->get_password();
                 
                $md5pwd = md5($password);
                $sql2 = "UPDATE maaking_users SET password='$md5pwd' WHERE username='$username' AND email='$email' LIMIT 1";
                $res = $db->sql_query($sql2);
                
                
                
                // Create the image
                $im = imagecreatetruecolor(400, 30);

                // Create some colors
                $white = imagecolorallocate($im, 255, 255, 255);
                $grey = imagecolorallocate($im, 128, 128, 128);
                $black = imagecolorallocate($im, 0, 0, 0);
                imagefilledrectangle($im, 0, 0, 399, 29, $white);

                // The text to draw
                $text = $password;
                // Replace path by your own font path
                $font = 'arial.ttf';

                // Add some shadow to the text
                imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

                // Add the text
                imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

                // Using imagepng() results in clearer text compared with imagejpeg()
                imagejpeg($im,"temp/pwd_$password.jpg");
                
                imagedestroy($im);
                //TODO: URL RELATIVA !!!
                $messaggio .= "<p>La tua nuova password per ReteDES.it è :</p>
                               <p>
                               <img SRC=\"http://retegas.altervista.org/gas2/utenti/temp/pwd_$password.jpg\">
                               </p>
                               <p>
                               Ricordati che c'è differenza tra lettere maiuscole e minuscole.
                               </p>";
                               
                
                manda_mail("["._SITE_NAME."]","retegas@altervista.org",$username,$email,"["._SITE_NAME."] - Nuova password",$messaggio);
                sleep(1);
                //unlink("temp/pwd_$password.jpg");
                go("sommario",null,"","?q=43");
                die;
             
         }else{ //user
             $msg="L'accoppiata username / email non è stata riconosciuta";
             
         }
    
     }else{ //captcha
         $msg="Il codice letto nel disegnino non è giusto.";
     }
    
    
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Assegno il titolo che compare nella barra delle info
$r->title = "Password Dimenticata";

$r->messaggio = $msg; 
    

//Questo è il contenuto della pagina
$r->contenuto = '<div class="ui-widget-content ui-state-error padding_6px ui-corner-all">
Questa pagina permette di generare una nuova password e di inviarla tramite mail all\'utente che ne fa richiesta.<br>
La nuova password sostituisce da subito quella vecchia.
                </div>
                <br>
                 <form class="retegas_form ui-corner-all" method="POST">
                    <div>
                    <h4>1</h4>
                    <label for="username">Inserisci il tuo nome utente</label>
                    <input type="text" name="username" value="'.$username.'" size="50"></input>
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
                    <input type="submit" name="submit" value="Inviami una nuova password" align="center" >
                    <input type="hidden" name="do" value="req">
                    <h5 title="'.$help_partenza.'">Inf.</h5>
                    </div>
                 </form>';
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>