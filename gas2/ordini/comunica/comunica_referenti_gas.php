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

if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
    go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
    exit;
}

if(!(_USER_PERMISSIONS&perm::puo_postare_messaggi)){
    go("ordini_form",_USER_ID,"Il tuo GAS non ti autorizza a mandare mail massive","?id_ordine=$id_ordine");
    exit;
}



if($do=="send_mail"){           // REFERENTE ORDIND-----> REFERENTI GAS
       
       // CONTROLLARE SE USER E' REFERENTE ORDINE   
          $da_chi = _USER_FULLNAME;
          $mail_da_chi = id_user_mail(_USER_ID);
        
          
        $descrizione_ordine = descrizione_ordine_from_id_ordine($id_ordine);
        
       $qry="  SELECT
                    maaking_users.fullname,
                    maaking_users.email,
                    maaking_users.id_gas
                    FROM
                    maaking_users
                    Inner Join retegas_referenze ON retegas_referenze.id_utente_referenze = maaking_users.userid
                    WHERE
                    retegas_referenze.id_ordine_referenze = '$id_ordine'
                    GROUP BY
                    maaking_users.fullname,
                    maaking_users.email,
                    maaking_users.id_gas
                    ";
        $result = $db->sql_query($qry);
        $lista_destinatari ="";
        while ($row = mysql_fetch_array($result)){
            if($gas<>$row[2]){
                $verso_chi[] = $row[0] ;
                $mail_verso_chi[] = $row[1] ;
                $lista_destinatari .= $row[0]." (".gas_nome($row[2])."); <br>";
                }

        }// END WHILE

        $soggetto = "["._SITE_NAME." - REFERENTE ORDINE] $da_chi per ordine $id_ordine ($descrizione_ordine)";
        
          manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id_ordine,_USER_ID,$msg_mail);
            
          $msg="Mail correttamente inviata a : <br>$lista_destinatari";
          go("ordini_form",_USER_ID,$msg,"?id_ordine=$id_ordine");

          //unset($do);
          //Header("Location: ".$RG_addr["ordini_aperti_form_old"]."?id=$id"); die();
          //exit;
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
    $titolo__form_mail="Manda un messaggio ai Referenti degli altri GAS di questo ordine";
            $qry="  SELECT
                    maaking_users.fullname,
                    maaking_users.email,
                    maaking_users.id_gas
                    FROM
                    maaking_users
                    Inner Join retegas_referenze ON retegas_referenze.id_utente_referenze = maaking_users.userid
                    WHERE
                    retegas_referenze.id_ordine_referenze = '$id_ordine'
                    GROUP BY
                    maaking_users.fullname,
                    maaking_users.email,
                    maaking_users.id_gas
                    ";
        $result = $db->sql_query($qry);
        $lista_destinatari ="";
        while ($row = mysql_fetch_array($result)){
            if($gas<>$row[2]){
                $lista_destinatari .= $row[0]." (".gas_nome($row[2])."); ";
            }
        }
        $posizione_estesa ="Altri referenti";
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