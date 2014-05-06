<?php


//Togliere quelli che non interessano
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_postare_messaggi)){
     pussa_via();
}

//---------------------------------------------------------SEND MAIL
if($do=="send_mail"){

       
        $qry=" SELECT 
        maaking_users.fullname,
        maaking_users.email,
        maaking_users.userid
        FROM
        maaking_users
        WHERE id_gas='"._USER_ID_GAS."'
        ";
        $result = $db->sql_query($qry); 
        while ($row = mysql_fetch_array($result)){
        
            //echo "ART : $articolo =". $row[0] ." - ". $row[1]."<br />";
            
            if(in_array($row[2], $box_user)){
                
                if(!in_array($row[0], $verso_chi)){
                    $verso_chi[]=$row[0];
                    $mail_verso_chi[]=$row[1];
                    $lista_destinatari .= $row[0]."<br>";
                }
                
            }
            
        }
        
    
    
    
  

//MAIL
$da_chi = fullname_from_id(_USER_ID);
$mail_da_chi = id_user_mail(_USER_ID);
$descrizione_ordine = descrizione_ordine_from_id_ordine($id_ordine);
$soggetto = "["._SITE_NAME."] - [REFERENTE ORDINE] $da_chi per ordine $id_ordine ($descrizione_ordine)";
manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id_ordine,_USER_ID,$msg_mail);
            
$msg="Mail correttamente inviata a : <br>$lista_destinatari";
//echo $msg;    
go("gas_form",_USER_ID,$msg);
exit;    
}
//---------------------------------------------------------SEND MAIL

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Comunica solo ad alcuni utenti";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_ckeditor();
$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts[]='<script>
                        $("#output_1 input[type=checkbox]").live("change", function() {
                            $(this).closest("tr").toggleClass("selected");
                        }).filter(":checked").each(function() {
                            $(this).closest("tr").addClass("selected");
                        });
                    </script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$result = $db->sql_query("SELECT * FROM maaking_users WHERE id_gas='"._USER_ID_GAS."';");
     

    $gas_name = gas_nome(_USER_ID_GAS);

    

    

    $h .= " <div class=\"rg_widget rg_widget_helper\">

            <h3>Utenti $gas_name</h3>
            <form action=\"\" method=\"POST\">
            <table id=\"output_1\">

         <thead>
            <tr class=\"sinistra\">         
                <th>&nbsp;</th>
                <th>Stato</th>
                
                <th>Nome</th>
                <th>E-Mail</th>
                <th>Indirizzo</th>
                <th>Telefono</th>
            </tr>
         </thead>
         <tbody>";



       $riga=0;  

         while ($row = $db->sql_fetchrow($result)){

         $riga++;

            $d1 = "id_gas";
 
            $id_utente = $row["userid"];
            $fullname = $row["fullname"];
            $mail = $row["email"];
            $indirizzo = $row["country"]." - ".$row["city"];
            $tel = $row["tel"];
            $date2 = date("Y-m-d");
            $date1 = $row["last_activity"];
            $reg = conv_date_from_db($row["regdate"]);
            
            
            
                                       
            $gg_ina = number_format((int)floor(abs(strtotime($date2) - strtotime($date1))/(60*60*24)),0,"","");
            
            if($gg_ina>999){$gg_ina="Troppi...";}
    
            if(read_option_text($id_utente,"_USER_USA_CASSA ")=="SI"){
                $pal_cas="SI";
            }else{
                $pal_cas="NO";
            }
    
    
            if($row["isactive"]==0){
                $pal = "IN ATTESA";
            }
            if($row["isactive"]==1){
                $pal = "ATTIVO";
            }
            if($row["isactive"]==2){
                $pal = "SOSPESO";
            }
            if($row["isactive"]==3){
                $pal = "ELIMINATO";
            }
    
            $opz="<input type=\"checkbox\" name=\"box_user[]\" value=\"".$row["userid"]."\" ";

            $a = "
            <tr>
            <td>$opz</td>
            <td>$pal</td>
             
            <td><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode($id_utente)."\">$fullname</a></td>
            <td>$mail</td>
            <td>$indirizzo</td>
            <td>$tel</td>
            
            </tr>";
    
            if ($row["isactive"]<>1){
                if(_USER_PERMISSIONS & perm::puo_gestire_utenti){
                
                    $h .= $a;
                }
            }else{
                $h .= $a;
            }  

         }//end while

         $h.= " </tbody>
                </table>";
                $h .="<p></p>";
                $h .="<textarea class =\"ckeditor\" rows=\"5\" name=\"msg_mail\" cols=\"60\"></textarea>";
                $h .="<input type=\"hidden\" name=\"do\" value=\"send_mail\">";
                $h .="<br><input type=\"submit\" name=\"submit\" value=\"Invia il messaggio\" class=\"awesome large green\">";
               $h.="<div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>