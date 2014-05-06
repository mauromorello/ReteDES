<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
$id_ordine=CAST_TO_INT($id_ordine);

 


if($do=="send_mail"){           // REFERENTE ORDIND-----> REFERENTI GAS
       
       // CONTROLLARE SE USER E' REFERENTE ORDINE   
          $da_chi = _USER_FULLNAME;
          $mail_da_chi = id_user_mail(_USER_ID);
        
          $verso_chi = fullname_referente_ordine_globale($id_ordine); 
          $mail_verso_chi = mail_referente_ordine_globale($id_ordine);
          
                  
          $soggetto = "["._SITE_NAME."] - da $da_chi - Comunicazione";
          manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
            
          $msg="Mail correttamente inviata a $verso_chi";
          go("ordini_form",_USER_ID,$msg,"?id_ordine=$id_ordine");
          exit;
          
      }




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Comunica a tutti i referenti GAS";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_ckeditor();


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{
    $r->messaggio = $msg;
}
//Contenuto
            $titolo__form_mail="Manda un messaggio al referente globale ordine";
            $lista_destinatari = fullname_referente_ordine_globale($id_ordine);
            $posizione_estesa ="Referente Ordine";
            
$h = " <div class=\"rg_widget rg_widget_helper\">
            <h3>$titolo__form_mail<h3> 
                        
            ";         
$h.=  "
        
                
        <form method=\"POST\" action=\"\">
        <table>
        <tr class=\"odd\">
            <th>
            <input class=\"large awesome green\" style=\"margin:20px;\" type=\"submit\" value=\"Invia\">
            </th>
            <td><textarea class =\"ckeditor\" rows=\"5\" name=\"msg_mail\" cols=\"60\"></textarea></td>
        </tr>
        <tr class=\"odd\">
            <th>Il messaggio sarà inviato a:</th>
            <td style=\"font-size:.75em;\">$lista_destinatari</td>
        </tr>
        </table>
        <input type=\"hidden\" name=\"do\" value=\"send_mail\">
        <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
        <center>
        </center>
        </form>
        </div>

        ";         

        
        
        
//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>